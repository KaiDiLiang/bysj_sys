<?php
/**
 * token模型
 */
namespace app\common\model;

use think\Model;
use Blocktrail\CryptoJSAES\CryptoJSAES;

class Token extends Model
{
        protected $name = 'auth_token';
        
        public static $instance = null;
        protected static $tokenField = ['userId', 'userRoleId', 'userSalt'];

        static public function getInstance()
        {
            if (!self::$instance) {
                self::$instance = new static();
            }
            return self::$instance;
        }

        /**
         * 解析token，并验证token的有效性
         */
        static public function parse($token)
        {
            $token = CrytoJSAER::decrypt($token, SUMMUY_KEY);
            if (!$token) return false;
            $token = unserialize($token);
            $rows = self::where(['user_id' => $token['userId']])->field('token_val, token_invali_at')->find();
            $user = User::where(['user_id' => $token['userId']])->field('user_salt, user_role_id')->find();
            //返回的token时间<现在时间，则token过期
            if ($rows->token_invali_at < time() || $row->token_val !== $token['token'] || $user
                ->user_salt !== $token['userSalt'] || $user->user_role_id !== $token['userRoleId']) {
                return false;
            }
            return $token;
        }

        /**
         * Token的生成
         * (...$parmas)可变参数传参,传进来的参数以数组的形式传入
         */
        public function generate(...$parmas)
        {
            /**
             * array_combine()合并数组,首个数组的元素作第二个数组的key
             * token存放于$parmas,含有id,role_id,userSalt
             */
            $parmas = array_combine(self::$tokenField, $parmas);
            //uniqid(),用微秒给token加上唯一的字符串
            $token_val = uniqid(microtime() . $parmas['userSalt'], true);
            //token生成的时间戳
            $token_create_at = time();
            //token的有效时间
            $token_invali_at = $token_create_at + 3600;
            $user_id = &$parmas['userId'];
            
            //查找token是否存在
            $token = self::where(['user_id' => $parmas['userId']])->find();
            if (is_null($token)) {
                $this->save(compact('token_val', 'token_create_at', 'token_invali_at', 'user_id'));
            } else {
                $token->save(compact('token_val', 'token_invali_at'));
            }
            /**
             * 拿到token的加密明文
             * 序列化并加密形成密文
             */
            $parmas['token'] = $token_val;
            return CryptoJSAES::encrypt(serialize($parmas), SUMMUY_KEY);
        }
}
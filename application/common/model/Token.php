<?php

namespace app\common\model;

use think\Modle;
use Blocktrail\CryptoJSAES\CryptoJSAES;

class Token extends Modle
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
         * Token的生成
         * (...$parmas)可变参数传参
         */
        public function generate(...$parmas)
        {
            /**
             * array_combine()合并数组
             * token存放于$parmas,含有id,role_id,userSalt
             */
            $parmas = array_combine(self::$tokenFiled, $parmas);
            //uniqid(),用微秒给token加上唯一的字符串
            $token_val = uniqid(miscrotime() . $parmas['userSalt'], true);
            //token生成的时间戳
            $token_create_at = time();
            //token的有效时间
            $token_invali_at = $token_create_at + 3600;
            $user_id = &$parmas['userId'];
            
            //查找token是否存在
            $token = self::where(['user_id' => $parmas['userId']]->find());
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
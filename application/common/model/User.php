<?php
/**
 * 用户模型
 * 登录逻辑
 */
namespace app\common\model;

use think\Model;
use think\Request;

class User extends Model
{
    protected $name = 'user_accounts';

    public static $instance;
    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();  //new self
        }
        return self::$instance;
    }
    
    /**
     * 查询前的事件
     * beforeQueryEvent(),(指定传进的变量是谁派生出来的实例)
     *field('', true),不查该项
     */
    public function beforeQueryEvent(Model $instance)
    {
        return $instance->field('user_password', 'user_salt', true);
    }

    /**
     * 用户登录逻辑
     * @author <kai930322@outlook.com>
     */
    public function login(Request $request)
    {
        //限制传入的数据
        $user_account = $request->post('u_account', '', 'trim');
        $user_password = $request->post('u_password', '', 'trim');
        $user_type = $request->post('u_type', '', 'trim');

        /**在user登录前判断输入的账号前6位
         * 不全等"admin@"时执行switch判断user_type
         */
        if (substr($user_account, 0 , 6) === 'admin@') {
            $user_type = 'A';
        } else {
            switch ($user_type) {
                case '_student':
                default;
                    $user_type = 'S';
                    break;
                case '_teacher':
                    $user_type = 'T';
                    break;
            }
        }    
        //self::where(['user_account' => $user_account, 'user_type' => $user_type])->find();
        /**传进的变量变成数组
         * 变量名=>下标，变量值=>数组对应下标的值
         */
        $user = self::where(compact('user_account', 'user_type'))->find();
               
        /*用户是否存在*/
        if (!is_null($user)) {
            if ($user->user_enable === 'Y') {
                //密码加密
                $passwordHash = new \Pentagonal\PhPass\PasswordHash();
                /**
                 * 密码 = 用户明文密码 + 用户密码盐
                 */
                $isPasswordMatch = $passwordHash->checkPassword(
                  $user_password . $user->user_salt,
                    $user->user_password
                );
                if ($isPasswordMatch) {
                    /**更新用户表的信息
                     * time()获取时间戳
                     * lastIp, 加密后的salt盐
                     */
                    $user->user_last_at = time();
                    //ip()传值转换为ipv4样式
                    $user->user_last_ip = $request->ip(1); 
                    //uniqid()生产唯一随机字符串(微秒数.熵),拿到加密的盐
                    $user->user_salt = $passwordHash->hasPassword(uniqid(microtime(), true));
                    //拿到加密后的密码
                    $user->user_password = $passwordHash->hasPassword($user_password . $user->user_salt);
                    //拿到的数据写入数据库
                    $user->save();

                    /**
                     * 生成Token,索引数组形式
                     *返回加密后的token密文，string
                     */
                    $token = Tonken::getInstance()->generate($user->user_id, $user->user_role_id, $user->user_salt);
                    return API::setJson('login-success', compact('token'));
                } else {
                    //todo::账号密码不匹配
                    return API::setJson('account-password-error');
                }
            } else {
                //todo::账号异常
                return API::setJson('account-status-error');
            }
        } else {
            //todo::账号不存在
            return API::setJson('account-not-exists');
        }
    }
}
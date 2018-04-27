<?php

namespace app\common\model;

use think\Modle;

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
             * 
             */
            $parmas = array_combine(self::$tokenFiled, $parmas);
        }
}
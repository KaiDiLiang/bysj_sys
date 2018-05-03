<?php

namespace app\admin\controller;

use app\common\controller\BaseController as Base;
use app\common\model\Student as StudentModel; 

class Student extends Base
{
    //初始化方法
    public function initialize()
    {
        /**
         * parent::initialize,可以用到父类的该方法
         * 没用该句则等于重写该方法
         * StudentModel::class,类名返回出去而不是类的实例
         */
        parent::initialize();
        $this->model = StudentModel::class;
    }

    public function getInfo()
    {
        //直接拿学号来进行
        return $this->_setResponse(
            StudentModel::getInstance()->profile(
                $this->request->get('s_number', '', 'trim')
            )
        );
    }
}
<?php

namespace app\admin\controller;

use app\common\controller\BaseController as Base;
use app\common\model\Student as StudentModel; 

class Student extends Base
{
    public function getInfo()
    {
        return $this->_setResponse(
            StudentModel::getInstance()->profile(
                $this->request->get('s_number', '', 'trim')
            )
        );
    }
}
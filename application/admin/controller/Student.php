<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use app\common\model\User as UserModel;

class User extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->model = UserModel::class;
    }
}
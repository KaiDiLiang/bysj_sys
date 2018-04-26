<?php
/**
 * 后台入口控制器
 */
namespace app\admin\controller;

use think\controller;

class Index extends Controller
{
    public function index()
    {

    }

    public function login()
    {
        if ($this->request-isPost()) {
            User::getInstance()->login();
        }
    }
}
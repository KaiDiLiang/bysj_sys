<?php
/**
 * 后台入口控制器
 * @author KaiDi <kai930322@outlook.com>
 */
namespace app\admin\controller;

use app\common\controller\BaseController as Base;
use app\common\model\User;
use app\common\model\Classs;

class Index extends Base
{
    public function initialize()
    {
        parent::initialize();
        $this->noNeedLogin = ['login'];
    }

    public function index()
    {

    }

    public function login()
    {
        if ($this->request->isPost()) {
            return $this->_setResponse(
                User::getInstance()->login($this->request)
            );
        }
    }

    public function getClassList()
    {
        Classs::getInstance()->getClassList();
    }
}
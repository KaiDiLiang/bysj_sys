<?php

namespace app\admin\controller;

use app\common\controller\BaseController;
use app\common\model\Rule as RuleModel;

class Rule extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->model = RuleModel::class;
    }
}
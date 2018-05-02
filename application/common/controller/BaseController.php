<?php

namespace app\common\controller;

use think\Controller;
use think\Response\Json;

class BaseController extends Controller
{
    /**
     * 设置响应
     * @param $response mixed
     * @param Json
     */
    protected function _setResponse($response)
    {
        //如果$response属于Response\Json派生出来
        if ($response instanceof Json) {
            return $response;
        } else {
            //todo::非json响应
        }
    }
}
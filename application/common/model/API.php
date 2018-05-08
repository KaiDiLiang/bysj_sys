<?php
/**
 * api模型
 */
namespace app\common\model;

use think\Model;

class API extends Model
{
    protected $name = 'sys_api';

    static private $instance;

    static public function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    /**
     * json响应的方法
     * @param $code string 响应的标识码
     * @param $data array 响应的数据，可选
     * @param $http_code int http响应码，默认200
     * @param $header array 附带的HTTP头，可选
     * @author KaiDI <kai930322@outlook.com> 
     * 
     * @return think\Response\Json
     */
    static public function setJson($code, array $data = [], $http_code = 200, array $header = [], $isEnd = false)
    {
        /**
         * 查找响应码
         *strtolower(),转换为小写
         */
        $result = self::where(['api_code' => strtolower($code)])->field('api_short_code as code, api_remark as remark')->find();
       
        /**
         * $result = false时,
         * explode(),把API返回的字符串分割
         * end()，取最后一个
         */
        if (!$result) {
            $temp = explode('-', strtolower($code));
            $type = end($temp);
            if ($type === 'success') {
                $result = ['code' => 0, 'remark' => $code];
            } else {
                $result = ['code' => -1, 'remark' => $code];
            }
        }
        $result['code'] = intval($result['code']);
        $response = compact('result', 'data');
        if ($isEnd) {
            //显示页面状态码
            http_response_code($http_code);
            header('Content-Type', 'application/json;charset=utf-8');
            echo json_encode($response);
        } else {
            return json($response)->code($http_code)->header($header);
        }
    }
}
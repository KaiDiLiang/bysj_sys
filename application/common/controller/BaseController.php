<?php
/**
 * 后台基础控制器
 * @author KaiDi <kai930322@outlook.com>
 */
namespace app\common\controller;

use think\Controller;
use think\Response\Json;
use app\common\model\API;
use app\common\model\User as UserModel;

class BaseController extends Controller
{
    //子类调用父类
    protected $model;

    public function initialize()
    {
        //todo::权限控制
        parent::initialize();
        UserModel::getInstance()->checkPermission($this->request);
    }
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

    /**
     * 从post中获取查询的参数
     */
    final protected function buildParams()
    {
        $filter = $this->request->post('filter', '', 'trim');
        $limit = $this->request->post('limit', '25', 'intval');
        $page = $this->request->post('page', 1, 'intval');
        $order = $this->request->post('order', 'id', 'trim');
        $sort = $this->request->post('sort', 'DESC', 'trim');
        $op = $this->request->post('op', '', 'trim');

        $filter = $filter ? json_decode($filter, true) : [];
        $op = $op ? json_decode($op, true) : [];
        $where = [];

        /**
         * 组合查询字段条件 where语句
         * "%($str)%",模糊查询
         */
        foreach ($filter as $key => $val) {
            if (isset($op[$key])) {
                switch ($op[$key]) {
                    case '=';
                    case '!=';
                    default:
                        $where[] = [$key, $op[$key], $val];
                        break;
                    case '>=';
                    case '<=';
                    case '>';
                    case '<';
                        $where[] = [$key, $op[$key], intval($val)];
                        break;
                    case 'LIKE';
                    case 'NOT LIKE';
                        $str = (string)$val;
                        $where[] = [$key, $op[$key], "%($str)%"];
                        break;
                }
              } else {
                $where[] = [$key , '=', $val];
            }
        }
        //必须指定$this->model是哪个类，否则拿不到getPk(),默认会拿$order('id')
        if ($this->model) {
            $order = $this->model->getPk();
        }
        return [$where, $filter, $limit, $page, $order, $sort];
    }

    /**
     * 数据查询入口
     * @return json
     */
    public function index()
    {
        if ($this->request->isPost()) {
            list($where, $limit, $page, $order, $sort) = $this->buildParams();
            if ($this->model) {
                $instance = method_exists($this->model, 'getInstance') ? $this->model::getInstance() : (new $this->model);
            }
            if (method_exists($this->model, 'beforeQueryEvent')) {
                $instance = $instance->beforeQueryEvent($instance);
            }
            $rows = $instance->where($where)->page($page)->limit($limit)->order($order, $sort)->select();
            $tree = $this->request->post('tree', '', '');
            //toArray(),对象转换为数组形式
            if ($tree === '√') {
                $rows = list_to_tree($rows->toArray(), 'id', 'parent_id');
            }
            return $this->_setResponse(
                API::setJson('get-data-success', compact('rows'))
            );
        }
    }
    
    public function add()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
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

    /**
     * api返回码前缀 比如 data-insert-
     * @var string|null
     */
    protected $apiPrefix = null;

    //子类调用父类
    protected $model;

    /**
     * 不需要登录的方法 action
     * @var array
     * 给予权限判断使用
     */
    protected $noNeedLogin = [];

    public function initialize()
    {
        //todo::权限控制
        parent::initialize();
        //如果访问过来的URL是$this->noNeedLogin(),则不需要进行登录
        if (in_array($this->request->action, $this->noNeedLogin)) {
            //实现权限控制
           $result = UserModel::getInstance()->checkPermission($this->request);
           if ($result !== true) {
               return $this->_setResponse($result);
           }
        }
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
     * 封装api响应
     * @param $type boolean
     * @param $data array
     * 
     * @return think\Response\Json
     */
    final protected function setAPIResponse($tyep = true, array $data = [])
    {
        $perfix = $this->apiPrefix 
            ? $this->apiPrefix 
            : join([$this->request->module(), $this->request->controller(),$this->request->action()], '-');
        
        return $this->_setResponse(
            API::setJson($perfix . $tyep ? 'success' : 'error', $data)
        );
    }

    /**
     * 从post中获取查询的参数
     */
    final protected function buildParams()
    {
        $filter = $this->request->post('filter', '', 'trim');
        $limit = $this->request->post('limit', '25', 'intval');
        $page = $this->request->post('page', 1, 'intval');
        $order = $this->request->post('order', '', 'trim');
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
        /**
         * 必须指定$this->model是哪个类，否则拿不到getPk(),默认会拿$order('id')
         * 该model是一个类名
         *
         *   if ($this->model) {
         *      $order = $this->model->getPk();
         *  }
         */
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
            //自动判断表的id自增来查询
            $order = $order ? $instance->getPk() : $order;

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
        if ($this->request->isPost()) {
            $this->apiPrefix = 'data-insert-';
            $row = $this->request->post('row/a', null, 'trim');
            if (!$rows) return $this->_setResponse(API::setJson('no-data-error'));
            if ($this->model) {
                $instance = method_exists($this->model, 'getInstance') ? $this->model::getInstance() : (new $this->model);
            }
            $instance = $instance->data($row);
            if (method_exists($this->model, 'beforeAddEvent')) {
                $instance = $instance->beforeAddEvent($instance);
            }
            if ($instance->save() > 0) {
                return $this->setAPIResponse();
            } else {
                return $this->setAPIResponse(false, config('app_debug') ? ['msg' => $instance->getError()] : []);
            }
        } else {
            return $this->_setResponse(
                API::setJson('acesss-request-error')
            );
        }
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
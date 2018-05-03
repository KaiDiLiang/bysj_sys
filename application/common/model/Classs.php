<?php

namespace app\common\model;

use think\Model;
use think\Db;

class Classs extends Model
{
    protected $name = 'class_list';

    static private $instance;

    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    /**
     * 获取表的列表
     * join(class,mojor,department)
     */
    public function getClassList()
    {
        $class = Db::name('mid_cmj')->alias('a')
        ->join('__CLASS_LIST__ c', 'a.c_id = c.class_id')
        ->select();
    }
}
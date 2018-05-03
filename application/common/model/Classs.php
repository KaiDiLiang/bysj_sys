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
     * join(class_list,mojor_list,department_list)
     * 从中间表mid_cmj出发
     */
    public function getClassList()
    {
        $class = Db::name('mid_cmj')->alias('a')
            ->field('c.*, d.department_name, m.major_name')
            ->join('__CLASS_LIST__ c', 'a.c_id = c.class_id')
            ->join('__MAJOR_LIST__ m', 'a.j_id = m.major_id')
            ->join('__DEPARTMENT_LIST__ d', 'a.m_id = d.department_id')
            ->select();
        dump($class);exit;
    }
}
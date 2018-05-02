<?php

namespace app\common\model;

class Student extends User
{
    protected $name = 'user_student';

    static $instance = null;

    static public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    /**
     * 获取指定学生的详细信息
     * @param $studentNumber string
     * @author KaiDi <kai930322@outlook.com>
     */
    public function profile($studentNumber)
    {
        self::where(['student_number' => $studentNumber])->find();
        //info => $student,返回出去的结果
        return ApI::setJson('get-student-info-success', ['info' => $student]);
    }
}
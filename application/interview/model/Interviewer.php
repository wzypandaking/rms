<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午5:21
 */

namespace app\interview\model;

use app\common\model\Common;
use app\utils\Result;

class Interviewer extends Common
{

    public function _list($param, $start, $limit, $sortBy=array())
    {
        if ($start > 0) {
            $start -= 1;
        }
        return $this->where($param)->order($sortBy)->limit($start * $limit, $limit)->select();
    }

    public function total($param)
    {
        return $this->where($param)->count();
    }

    public function getAll()
    {
        return $this->select();
    }

    public function getById($id)
    {
        return $this->where(array('id'=>$id))->find();
    }

    public function add($interviewer)
    {
        $interviewerInfo = $this->where(array(
            'email' => $interviewer['email']
        ))->find();
        if ($interviewerInfo) {
            return Result::wrap("面试官已经存在", false, null);
        }
        $id = $this->insert($interviewer);
        return Result::wrap("创建成功", true, $id);
    }

    public function getByIds($interviewIdList)
    {
        return $this->where(array(
            'id'    =>  array('in', $interviewIdList)
        ))->select();
    }
}
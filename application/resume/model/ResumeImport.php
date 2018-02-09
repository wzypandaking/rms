<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 下午6:17
 */

namespace app\resume\model;


use app\common\model\Common;

class ResumeImport extends Common
{

    public function _list($param, $start, $limit, $sortBy = array())
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

    public function getByToken($token)
    {
        return $this->where(array(
            'token' =>  $token
        ))->select();
    }
}
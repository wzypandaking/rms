<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午3:20
 */
namespace app\common\controller;

use think\Db;
use think\Loader;

abstract class Common
{

    protected function _list($module, $param, $start, $limit, $sortBy=array())
    {
        return Loader::model($module)->_list($param, $start, $limit, $sortBy);
    }

    protected function _count($module, $param)
    {
        return Loader::model($module)->total($param);
    }
}
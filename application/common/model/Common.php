<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午5:25
 */
namespace app\common\model;

use think\Model;

abstract class Common extends Model
{

    public abstract function _list($param, $start, $limit, $sortBy=array());
    public abstract function total($param);


}
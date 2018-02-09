<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午5:12
 */

namespace app\interview\controller;


use app\common\controller\Common;
use think\Db;
use think\Loader;

class Index extends Common
{
    public function index()
    {
        return Loader::model('interviewer')->getAll();
    }
}
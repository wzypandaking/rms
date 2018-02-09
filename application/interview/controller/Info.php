<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/2
 * Time: 下午2:53
 */

namespace app\interview\controller;


use think\Loader;
use think\Request;

class Info
{
    public function index(Request $request)
    {
        return Loader::model('interviewer')->getById($request->param('interviewId'));
    }

}
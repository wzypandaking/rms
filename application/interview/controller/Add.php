<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/6
 * Time: 下午8:34
 */

namespace app\interview\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Add
{
    public function index(Request $request)
    {
        $name = $request->post("name");
        if (empty($name)) {
            return Result::wrap("姓名不能为空", false, null);
        }
        $mobile = $request->post("mobile");
        if (empty($mobile)) {
            return Result::wrap("联系方式不能为空", false, null);
        }
        $email = $request->post("email");
        if (empty($email)) {
            return Result::wrap("邮箱不能为空", false, null);
        }
        return Loader::model('interviewer')->add($request->post());
    }

}
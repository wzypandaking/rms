<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/9
 * Time: 上午11:16
 */

namespace app\resume\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Add
{

    public function index(Request $request)
    {
        $data = array(
            'name'  =>  $request->post("name"),
            'mobile'  =>  $request->post("mobile"),
            'email'  =>  $request->post("email"),
            'sex'  =>  $request->post("sex", null),
            'post'  =>  $request->post("post"),
            'employed_time' => $request->post("employed_time"),
            'education'  =>  $request->post("education", null),
            'school'  =>  $request->post("school", null),
            'status'    =>  0,
            'resume_file_path'  =>  $request->post("resume_file_path")
        );
        $insertNumber = Loader::model("resume")->batchImport(array(
            $data
        ));
        return Result::wrap("成功写入" . $insertNumber . "条", true, null);
    }

}
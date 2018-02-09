<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/1
 * Time: 下午5:50
 */

namespace app\book\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Lists
{

    public function index(Request $request)
    {
        $resumeId = $request->param("resumeId");
        $resumeInfo = Loader::model('resume/resume')->getById($resumeId);
        if (empty($resumeInfo)) {
            return Result::wrap("没有找到人员信息", false, null);
        }
        $interviewList = Loader::model("BookInterview")->sortByInterviewTime($resumeInfo);
        return Result::wrap("", true, $interviewList);
    }

}
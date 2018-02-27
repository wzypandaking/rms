<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/24
 * Time: 下午7:31
 */

namespace app\book\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Number
{

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $resumeId = $request->param("resumeId");
        $resumeInfo = Loader::model('resume/resume')->getById($resumeId);
        if (empty($resumeInfo)) {
            return Result::wrap("没有找到人员信息", false, null);
        }

        $latestBook = Loader::model("BookInterview")->latestBook($resumeInfo);
        if (empty($latestBook)) {
            return Result::wrap("没有安排面试", false, null);
        }
        $interviewerInfo = Loader::model("interview/interviewer")->getById($latestBook->interview_id);
        if (empty($interviewerInfo)) {
            return Result::wrap("没有找到面试官", false, null);
        }
        $numberOfBook = Loader::model("BookInterview")->numberOfBook($resumeInfo);

        return Result::wrap("", true, array(
            'interviewName'     =>  $interviewerInfo->name,
            'interviewMobile'   =>  $interviewerInfo->mobile,
            'bookType'          =>  $latestBook->type,
            'numberOfBook'      =>  $numberOfBook
        ));
    }

}
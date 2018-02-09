<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/2
 * Time: 下午4:48
 */

namespace app\resume\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Transfer
{

    public function confirm(Request $request)
    {
        //  原来的面试安排
        $bookId = $request->post("bookId");
        $bookInterviewInfo = Loader::model("book/BookInterview")->getById($bookId);
        if (empty($bookInterviewInfo)) {
            return Result::wrap("没有找到面试安排", false, null);
        }
        //  新的面试官
        $interviewerId = $request->post('interviewer');
        $interviewerInfo = Loader::model('interview/interviewer')->getById($interviewerId);
        if (empty($interviewerInfo)) {
            return  Result::wrap("没有找到面试官", false, null);
        }
        $resumeId = $bookInterviewInfo->resume_id;
        $resumeInfo = Loader::model('resume')->getById($resumeId);
        if (empty($resumeInfo)) {
            return  Result::wrap("没有找到求职者信息", false, null);
        }
        $bookTime = $request->post("book_interview_time");
        if (empty($bookTime)) {
            $bookTime = date("Y-m-d H:i:s", $bookInterviewInfo->book_time + 60);
        }
        //  重新预约
        Loader::model('book')->book($resumeInfo, $interviewerInfo, $bookTime);
        //  修改简历状态为已预约
        Loader::model('resume')->book($resumeInfo);

        Loader::model("book/BookInterview")->transfer($bookId);
        return Result::wrap("成功预约", true, null);
    }

}
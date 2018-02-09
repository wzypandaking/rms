<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午4:15
 */

namespace app\resume\controller;


use app\common\controller\Common;
use app\utils\Result;
use think\Loader;
use think\Request;

class Book extends Common
{
    public function confirm(Request $request)
    {
        $interviewerId = $request->post('interviewer');
        $interviewerInfo = Loader::model('interview/interviewer')->getById($interviewerId);
        if (empty($interviewerInfo)) {
            return  Result::wrap("没有找到面试官", false, null);
        }
        $resumeId = $request->post("resume_id");
        $resumeInfo = Loader::model('resume')->getById($resumeId);
        if (empty($resumeInfo)) {
            return  Result::wrap("没有找到求职者信息", false, null);
        }
        Loader::model('book')->book($resumeInfo, $interviewerInfo, $request->post("book_interview_time"));
        Loader::model('resume')->book($resumeInfo);
        return Result::wrap("成功预约", true, null);
    }
}
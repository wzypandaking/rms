<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/2
 * Time: 下午3:01
 */

namespace app\book\controller;


use app\utils\Result;
use think\Loader;
use think\Request;

class Feedback
{
    /**
     * 通过反馈面试结果
     * @param Request $request
     */
    public function confirm(Request $request)
    {
        $type = $request->post("type", -1);
        if (!in_array($type, array(0, 1, 2))) {
            return Result::wrap("参数错误", false, null);
        }

        $resumeInfo = Loader::model('resume/resume')->getById($request->post("resumeId"));
        if (empty($resumeInfo)) {
            return Result::wrap("没有找到简历", false, null);
        }
        $interviewerInfo = Loader::model("interview/interviewer")->getById($request->post("interviewId"));
        if (empty($interviewerInfo)) {
            return Result::wrap("没有找到面试官信息", false, null);
        }
        if ($type == 1) {           // 通过
            Loader::model("BookInterview")->pass($resumeInfo, $interviewerInfo, $request->post("result"));
        } else if ($type == 0) {    // 淘汰
            Loader::model("BookInterview")->eliminate($resumeInfo, $interviewerInfo, $request->post("result"));
            Loader::model("resume/resume")->eliminate($resumeInfo);
        } else if ($type == 2) {    // 通过 给offer
            Loader::model("BookInterview")->passAndOffer($resumeInfo, $interviewerInfo, $request->post("result"));
            Loader::model("resume/resume")->offer($resumeInfo);
        } else {
            // 非正常参数
        }
        return Result::wrap("成功", true, null);
    }

}
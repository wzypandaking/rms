<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/2
 * Time: 上午10:11
 */

namespace app\book\model;


use app\common\model\Common;

class BookInterview extends Common
{

    public function _list($param, $start, $limit, $sortBy=array())
    {
        if ($start > 0) {
            $start -= 1;
        }
        return $this->where($param)->order($sortBy)->limit($start * $limit, $limit)->select();
    }

    public function total($param)
    {
        return $this->where($param)->count();
    }

    public function getById($id)
    {
        return $this->where(array(
            "id"    =>  $id
        ))->find();
    }

    public function sortByInterviewTime($resumeInfo)
    {
        return $this->where(array(
            'resume_id' =>  $resumeInfo->id
        ))->order(array(
            'book_time'    =>  'desc'
        ))->select();
    }

    /**
     * 通过面试
     * @param $resumeInfo
     */
    public function pass($resumeInfo, $interviewerInfo, $result)
    {
        $this->save(array(
            'type'    =>  2,
            'result'    =>  $result,
            'interview_time'    =>  time()
        ),array(
            'resume_id'    =>  $resumeInfo->id,
            'interview_id'  =>  $interviewerInfo->id,
            'type'  =>  0,
        ));
    }

    /**
     * 面试不通过，就会执行淘汰
     * @param $resumeInfo
     */
    public function eliminate($resumeInfo, $interviewerInfo, $result)
    {
        $this->save(array(
            'type'    =>  1,
            'result'    =>  $result,
            'interview_time'    =>  time()
        ), array(
            'resume_id'    =>  $resumeInfo->id,
            'interview_id'  =>  $interviewerInfo->id,
            'type'  =>  0,
        ));
    }

    /**
     * 通过面试，并给offer
     * @param $resumeInfo
     */
    public function passAndOffer($resumeInfo, $interviewerInfo, $result)
    {
        $this->save(array(
            'type'    =>  3,
            'result'    =>  $result,
            'interview_time'    =>  time()
        ),array(
            'resume_id'    =>  $resumeInfo->id,
            'interview_id'  =>  $interviewerInfo->id,
            'type'  =>  0,
        ));
    }

    /**
     * 将面试转发给其他面试官
     * @param $resumeInfo
     */
    public function transfer($id)
    {
        $this->save(array(
            'type'    =>  4,
            'interview_time'    =>  time()
        ), array(
            'id'    =>  $id
        ));
    }


    public function analysisByInterviewer($interviewerIdList)
    {
        return $this->field("count(id) as count, interview_id,type")->where(array(
            'interview_id'    => array('in', $interviewerIdList)
        ))->group("interview_id,type")->select();
    }


}
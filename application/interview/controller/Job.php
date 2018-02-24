<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/6
 * Time: 下午9:28
 */

namespace app\interview\controller;


use app\common\controller\Common;
use app\utils\PageResult;
use think\Loader;
use think\Request;

class Job extends Common
{
    public function index(Request $request)
    {
        $module = 'book/BookInterview';
        $param = array(
            'type'  =>  array('not in', array(4))
        );
        $sort = array(
            'book_time'=>'asc',
        );

        $searchParam = array();
        $this->processSearchParam($request, $searchParam);

        $param = array_merge($param, $searchParam);
        $start = $request->param("page", 1);
        $limit = $request->param("limit", 10);
        $list = $this->_list($module, $param, $start, $limit, $sort);
        return PageResult::wrap($this->_count($module, $param), $this->processResume($list));
    }

    private function processSearchParam(Request $request, & $searchParam)
    {
        $time = $request->param("time");
        if ($time === 'all') {
            $searchParam = array();
        } else if ($time === 'tomorrow') {
            $searchParam = array(
                'book_time' => array("between", array(
                    mktime(0,0,0,date('m'),date('d') + 1,date('Y')),
                    mktime(0,0,0,date('m'),date('d') + 2,date('Y')) - 1,
                ))
            );
        } else if ($time === 'yesterday'){
            $searchParam = array(
                'book_time' => array("between", array(
                    mktime(0,0,0,date('m'),date('d') - 1,date('Y')),
                    mktime(0,0,0,date('m'),date('d'),date('Y')) - 1,
                ))
            );
        } else {
            $searchParam = array(
                'book_time' =>  array(
                    'between', array(
                        mktime(0,0,0,date('m'),date('d'),date('Y')),
                        mktime(0,0,0,date('m'),date('d') + 1,date('Y')) - 1,
                    )
                )
            );
        }

        $interviewId = $request->param("interviewId", -1);
        if ($interviewId != -1) {
            $searchParam['interview_id'] = intval($interviewId);
        }
    }

    private function processResume($list)
    {
        $interviewMap = $this->getInterviewMap($list);
        $resumeMap = $this->getResumeMap($list);

        $resultList = array();
        foreach ($list as $value) {
            $data = array();
            if ($resumeMap[$value->resume_id]) {
                $data = array_merge($value->getData(), $resumeMap[$value->resume_id]->getData());
            } else {
                $data = $value->getData();
            }
            $data['book_id'] = $value->id;
            $data['interview_name'] = $interviewMap[$value->interview_id]->name;
            $data['interview_mobile'] = $interviewMap[$value->interview_id]->mobile;
            array_push($resultList, $data);
        }
        return $resultList;
    }

    private function getResumeMap($list)
    {
        $resumeIdList = array();
        foreach ($list as $item) {
            array_push($resumeIdList, $item->resume_id);
        }
        $resumeList = Loader::model("resume/resume")->getByIds($resumeIdList);
        $resumeMap = array();
        foreach ($resumeList as $item) {
            $resumeMap[$item->id] = $item;
        }
        return $resumeMap;
    }

    private function getInterviewMap($list)
    {
        $interviewIdList = array();
        foreach ($list as $item) {
            array_push($interviewIdList, $item->interview_id);
        }
        $interviewList = Loader::model("interviewer")->getByIds($interviewIdList);
        $interviewMap = array();
        foreach ($interviewList as $item) {
            $interviewMap[$item->id] = $item;
        }
        return $interviewMap;
    }
}
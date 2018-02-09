<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/9
 * Time: 下午2:39
 */

namespace app\book\controller;


use app\utils\Excel;
use think\Loader;
use think\Request;

class Download
{

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

    public function index(Request $request)
    {
        $module = 'book/BookInterview';
        $sort = array(
            'book_time'=>'asc',
        );
        $searchParam = array();
        $this->processSearchParam($request, $searchParam);
        $filename = "book/全部面试反馈.xlsx";
        if (isset($searchParam['book_time'])) {
            $filename = "book/" . date("Ymd", $searchParam['book_time'][1][0]) . "_面试反馈.xlsx";
        }

        $list = Loader::model($module)->order($sort)->where($searchParam)->select();
        $list = $this->processResume($list);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition:attachment;filename="'.basename($filename).'"');
        header("Content-Transfer-Encoding:binary");

        $filename = Excel::dump($filename, array(
            'name'  => '姓名',
            'sex'   =>  '性别',
            'mobile'    =>  '联系方式',
            'post'  =>  '应聘职位',
            'book_time' =>  '预约时间',
            'interview_time'    =>  '面试时间',
            'interview_name'    =>  '面试官',
            'type'  =>  '状态',
            'result'    =>  '面试反馈',
        ), $list);
        readfile($filename);
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
            //0:未面试 1：不通过 2：通过 3：给offer 4：转移面试人
            if($value->type == 0) {
                $data['type'] = '未面试';
            } else if ($value->type == 1) {
                $data['type'] = '淘汰';
            } else if ($value->type == 2) {
                $data['type'] = '通过';
            } else if ($value->type == 3) {
                $data['type'] = 'offer';
            } else if ($value->type == 4) {
                $data['type'] = '转移';
            } else {
                $data['type'] = '未知状态';
            }

            if ($data['sex'] == 1) {
                $data['sex'] = '男';
            } else if ($data['sex'] == 2) {
                $data['sex'] = '女';
            }

            if ($value->book_time) {
                $data['book_time'] = date('Y年m月d日 H:i', $value->book_time);
            }
            if ($value->interview_time) {
                $data['interview_time'] = date('Y年m月d日 H:i', $value->interview_time);
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
        $interviewList = Loader::model("interview/interviewer")->getByIds($interviewIdList);
        $interviewMap = array();
        foreach ($interviewList as $item) {
            $interviewMap[$item->id] = $item;
        }
        return $interviewMap;
    }
}
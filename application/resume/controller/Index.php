<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 上午10:03
 */
namespace app\resume\controller;
use app\common\controller\Common;
use app\utils\PageResult;
use think\Exception;
use think\Loader;
use think\Request;

class Index extends Common
{

    public function index(Request $request)
    {
        $module = 'resume';
        $param = array(
            'status'    =>  array('not in', array(4))
        );

        $name = $request->get("name");
        $searchParam = array();
        if (!empty($name)) {
            $searchParam ['name'] = array('like', "%" . $name . "%");
        }
        $mobile = $request->get("mobile");
        if (!empty($mobile)) {
            $searchParam ['mobile'] = $mobile;
        }
        $status = $request->get("status", -1);
        if ($status != -1) {
            $searchParam['status'] = intval($status);
        }

        $param = array_merge($param, $searchParam);

        $start = $request->param("page", 1);
        $limit = $request->param("limit", 10);
        return PageResult::wrap($this->_count($module, $param), $this->processBookInterview($this->_list($module, $param, $start, $limit)));
    }

    private function processBookInterview($list)
    {
        $resultList = array();
        foreach ($list as $key=>$item) {
            $resumeInfo = $item->getData();
            try {
                $resumeInfo = array_merge($resumeInfo, $this->numberOfBookInterview($item->id));
            } catch (Exception $e) {

            }
            array_push($resultList, $resumeInfo);
        }
        return $resultList;
    }

    /**
     * 获取当前面试次数
     * @param $resumeId
     * @return array
     * @throws Exception
     */
    private function numberOfBookInterview($resumeId)
    {
        $resumeInfo = Loader::model('resume/resume')->getById($resumeId);
        if (empty($resumeInfo)) {
            throw new Exception("没有找到人员信息");
        }

        $latestBook = Loader::model("book/BookInterview")->latestBook($resumeInfo);
        if (empty($latestBook)) {
            throw new Exception("没有安排面试");
        }
        $interviewerInfo = Loader::model("interview/interviewer")->getById($latestBook->interview_id);
        if (empty($interviewerInfo)) {
            throw new Exception("没有找到面试官");
        }
        $numberOfBook = Loader::model("book/BookInterview")->numberOfBook($resumeInfo);

        return array(
            'interview_name'     =>  $interviewerInfo->name,
            'interview_mobile'   =>  $interviewerInfo->mobile,
            'book_type'          =>  $latestBook->type,
            'number_of_book'     =>  $numberOfBook
        );
    }

}
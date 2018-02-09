<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/6
 * Time: 下午4:33
 */

namespace app\interview\controller;


use app\common\controller\Common;
use app\utils\PageResult;
use think\Loader;
use think\Request;

class Lists extends Common
{
    public function index(Request $request)
    {
        $module = 'interviewer';
        $param = array();

        $searchParam = array();
        // todo 处理筛选条件

        $param = array_merge($param, $searchParam);

        $start = $request->param("page", 1);
        $limit = $request->param("limit", 10);
        $list = $this->_list($module, $param, $start, $limit);
        return PageResult::wrap($this->_count($module, $param, $start, $limit), $this->analysis($list));
    }

    private function analysis(& $list)
    {
        $idList = array();
        foreach ($list as $key=>$item) {
            array_push($idList, $item->id);
        }

        $analysisResult = Loader::model("book/BookInterview")->analysisByInterviewer($idList);

        $analysisResultMap = array();
        foreach ($analysisResult as $item) {

            if (isset($analysisResultMap[$item->interview_id])) {
                $analysisResultMap[$item->interview_id]['total'] += $item->count;
            } else {
                $analysisResultMap[$item->interview_id] = array(
                    'total' =>  $item->count,
                    'un_interview'  =>  0,
                    'eliminate' =>  0,
                    'pass'  =>  0,
                    'offer' =>  0,
                    'transfer'  =>  0,
                );
            }

            switch ($item->type) {
                case 0:
                    //  未面试
                    if (isset($analysisResultMap[$item->interview_id]['un_interview'])) {
                        $analysisResultMap[$item->interview_id]['un_interview'] += $item->count;
                    } else {
                        $analysisResultMap[$item->interview_id]['un_interview'] = $item->count;
                    }
                    break;
                case 1:
                    //  淘汰
                    if (isset($analysisResultMap[$item->interview_id]['eliminate'])) {
                        $analysisResultMap[$item->interview_id]['eliminate'] += $item->count;
                    } else {
                        $analysisResultMap[$item->interview_id]['eliminate'] = $item->count;
                    }
                    break;
                case 2:
                    //  通过
                    if (isset($analysisResultMap[$item->interview_id]['pass'])) {
                        $analysisResultMap[$item->interview_id]['pass'] += $item->count;
                    } else {
                        $analysisResultMap[$item->interview_id]['pass'] = $item->count;
                    }
                    break;
                case 3:
                    //  offer
                    if (isset($analysisResultMap[$item->interview_id]['offer'])) {
                        $analysisResultMap[$item->interview_id]['offer'] += $item->count;
                    } else {
                        $analysisResultMap[$item->interview_id]['offer'] = $item->count;
                    }
                    break;
                case 4:
                    //  转移
                    if (isset($analysisResultMap[$item->interview_id]['transfer'])) {
                        $analysisResultMap[$item->interview_id]['transfer'] += $item->count;
                    } else {
                        $analysisResultMap[$item->interview_id]['transfer'] = $item->count;
                    }
                    break;
            }
        }
        $resultList = array();
        foreach ($list as $key=>$value) {
            if (isset($analysisResultMap[$value->id])) {
                array_push($resultList, array_merge($value->getData(), $analysisResultMap[$value->id]));
            } else {
                array_push($resultList, $value->getData());
            }
        }
        return $resultList;
    }

}
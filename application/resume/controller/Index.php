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
        return PageResult::wrap($this->_count($module, $param, $start, $limit), $this->_list($module, $param, $start, $limit));
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/2
 * Time: 下午6:09
 */

namespace app\resume\controller;


use app\common\controller\Common;
use app\utils\PageResult;
use think\Request;

class Blacklist extends Common
{

    public function lists(Request $request) {
        $module = 'resume';
        $param = array(
            'status'    =>  4
        );
        $start = $request->param("page", 1);
        $limit = $request->param("limit", 10);
        return PageResult::wrap($this->_count($module, $param, $start, $limit), $this->_list($module, $param, $start, $limit));
    }

}
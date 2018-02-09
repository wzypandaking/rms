<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午3:03
 */
namespace app\utils;

class PageResult
{

    public static function wrap($total, $data)
    {
        return array(
            'code'  =>  0,
            'msg'   =>  '',
            'count' =>  $total,
            'data'  =>  $data,
        );
    }

}
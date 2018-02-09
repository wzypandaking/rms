<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/1
 * Time: 下午2:36
 */

namespace app\utils;


class Result
{

    public static function wrap($message, $success, $data)
    {
        return array(
            'success'   =>  $success,
            'message'   =>  $message,
            'data'      =>  $data
        );
    }
}
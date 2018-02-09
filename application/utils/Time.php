<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/6
 * Time: 上午9:54
 */

namespace app\utils;


class Time
{

    public static function toYmdHis($time)
    {
        return date("YmdHis", $time);
    }

    public static function microtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return intval(($usec + $sec) * 1000);
    }

}
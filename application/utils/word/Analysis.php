<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 上午10:29
 */

namespace app\utils\word;


interface Analysis
{
    /**
     * @param $file 全局路径
     * @return mixed
     */
    function analysis($file);
}
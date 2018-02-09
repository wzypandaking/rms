<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 下午1:23
 */

namespace app\utils\word;


class ShangRangAnalysis extends AbsAnalysis
{

    function splitFileName($filename)
    {
        return explode('-', $filename);
    }
}
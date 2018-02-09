<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 下午5:12
 */

namespace app\utils\word;


class ZhilianAnalysis extends AbsAnalysis
{

    function splitFileName($filename)
    {
        return explode("_", $filename);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/8
 * Time: 下午5:07
 */

namespace app\utils\word;


class AnalysisFactory
{

    /**
     * @param $file
     * @return Analysis
     */
    public static function load($file)
    {
        $class = "app\utils\word\ShangRangAnalysis";
        if (strpos($file, "智联") !== false) {
            $class = "app\utils\word\ZhilianAnalysis";
        }
        return new $class();
    }

}
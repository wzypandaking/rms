<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/5
 * Time: 下午8:18
 */

namespace app\utils;


class FileUtil
{

    public static function lists($dir, $deep=1, $maxDeep = 2) {

        if ($deep > $maxDeep) {
            return array();
        }

        $resource = opendir($dir);

        if (! $resource) {
            return array();
        }

        $files = array();
        while ($f = readdir($resource)) {
            if ($f == '..' || $f == '.') {
                continue;
            }
            $tmp = $dir . DIRECTORY_SEPARATOR . $f;
            if (is_dir($tmp)) {
                $tmp = FileUtil::lists($tmp, $deep + 1, $maxDeep);
                $files = array_merge($files, $tmp);
            } else {
                if (strpos($f, '.') == 0) {
                    continue;
                }
                array_push($files, $tmp);
            }
        }
        return $files;

    }
}
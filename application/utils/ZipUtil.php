<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/5
 * Time: 下午7:56
 */

namespace app\utils;


use PhpOffice\PhpWord\Shared\ZipArchive;

class ZipUtil
{

    public static function decompress($zipFile) {

        $zip = new ZipArchive();
        $resource = $zip->open($zipFile);

        if ($resource !== true) {
            return array();
        }

        $dir = dirname($zipFile);
        ! is_dir($dir) && mkdir($dir, 0777, true);
        if ($zip->extractTo($dir)) {
            return FileUtil::lists($dir);
        }
    }
}
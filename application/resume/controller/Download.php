<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/6
 * Time: 上午9:25
 */

namespace app\resume\controller;


use app\utils\Time;
use PhpOffice\PhpWord\Shared\ZipArchive;
use think\Config;
use think\Loader;
use think\Request;

class Download
{
    public function index(Request $request)
    {
        $resumeId = $request->param("resumeId", 0);
        if ($resumeId === 0) {
            exit("参数错误");
        }
        $resumeInfo = Loader::model("resume")->getById($resumeId);
        if (empty($resumeInfo)) {
            exit("没有找到有效信息");
        }
        $resumeFile = Config::get("upload_path") . DIRECTORY_SEPARATOR . $resumeInfo->resume_file_path;
        if (!file_exists($resumeFile)) {
            exit("没有找到简历");
        }
        $filenameArr = explode(".", basename($resumeFile));

        $filename = $resumeInfo->name . '.' . array_pop($filenameArr);
        header("Cache-Control: public");
        Header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename='.basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($resumeFile));
        readfile($resumeFile);

        //  zip 下载
//        $tempFile = Config::get("temp_path") . DIRECTORY_SEPARATOR . 'resume_'. Time::microtime() . '.zip';
//        $zip = new ZipArchive();
//        $resource = $zip->open($tempFile, ZipArchive::OVERWRITE|ZipArchive::CREATE);
//        if (! $resource) {
//            exit("服务器端不支持zip");
//        }
//
//        $zip->addFile($resumeFile, basename($resumeFile));
//        $zip->close();
//
//        header('Content-Type:text/html;charset=utf-8');
//        header('Content-disposition:attachment;filename=resume.zip');
//        readfile($tempFile);
//        header('Content-length:'. filesize($tempFile));
//        unlink($tempFile);
    }

}
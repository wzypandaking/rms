<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/5
 * Time: 下午5:16
 */

namespace app\resume\controller;


use app\common\controller\Common;
use app\utils\Excel;
use app\utils\PageResult;
use app\utils\Result;
use app\utils\Time;
use app\utils\word\AnalysisFactory;
use app\utils\ZipUtil;
use think\Config;
use think\Loader;
use think\Request;

class Import extends Common
{

    private function upload(Request $request)
    {
        $uploadFileObject = $request->file("file");
        if (empty($uploadFileObject)) {
            return Result::wrap("上传失败", false, null);
        }
        $uploadPath = Config::get('upload_path') . DIRECTORY_SEPARATOR . date('YmdHis');
        $info = $uploadFileObject->move($uploadPath);
        if (! $info) {
            return Result::wrap("服务端写入异常", false, null);
        }
        return $uploadPath . DIRECTORY_SEPARATOR . $info->getSaveName();
    }

    public function index(Request $request)
    {

        $uploadFileName = $this->upload($request);
        $files = ZipUtil::decompress($uploadFileName);
        $dataList = null;

        $fileMap = array();
        foreach ($files as $file) {
            $result = explode("/", $file);
            $fileName = array_pop($result);
            preg_match("/1\d{10}/", $fileName, $matches);
            if (!empty($matches)) {
                $fileMap[array_shift($matches)] = str_replace(Config::get("upload_path") . DIRECTORY_SEPARATOR, '', $file);
            }
            if (strcmp("import.xlsx", strtolower($fileName)) === 0) {
                $dataList = Excel::getDataListWithFormat($file, array(
                    'name',
                    'sex',
                    'education',
                    'school',
                    'post',
                    'employed_time',
                    'email',
                    'mobile'
                ));
            }
        }
        if ($dataList === null) {
            return Result::wrap("没有找到上传的数据", false, null);
        }
        unset($dataList[0]);
        foreach ($dataList as $key=>$data) {
            if ($data['sex'] == '男') {
                $dataList[$key]['sex'] = 1;
            } else {
                $dataList[$key]['sex'] = 2;
            }
            $dataList [$key]['resume_file_path'] = $fileMap[$data['mobile']];
        }
        $insertNumber = Loader::model("resume")->batchImport($dataList);
        return Result::wrap("成功写入" . $insertNumber . "条", true, null);
    }

    public function easy(Request $request)
    {
        $uploadFileName = $this->upload($request);
        $files = ZipUtil::decompress($uploadFileName);
        $token = Time::microtime();

        $analysisResultList = array();
        foreach ($files as $file) {
            if (strpos($file, '.zip')) {
                continue;
            }
            $analysis = AnalysisFactory::load($file);
            $result = $analysis->analysis($file);
            $result['resume_file_path'] = str_replace(Config::get("upload_path") . DIRECTORY_SEPARATOR, '', $file);
            $result['token'] = $token;
            array_push($analysisResultList, $result);
        }
        Loader::model("ResumeImport")->insertAll($analysisResultList);
        return Result::wrap("上传成功", true, $token);
    }

    public function check(Request $request)
    {
        $module = "ResumeImport";
        $param = array(
            'token' =>  $request->param("token")
        );
        $start = $request->param("page", 1);
        $limit = $request->param("limit", 10);
        return PageResult::wrap($this->_count($module, $param, $start, $limit), $this->_list($module, $param, $start, $limit));
    }

    public function modify(Request $request)
    {
        $data = array(
            'name'  =>  $request->post('name'),
            'mobile'  =>  $request->post('mobile'),
            'post'  =>  $request->post('post'),
            'email'  =>  $request->post('email'),
            'sex'   =>  $request->post("sex", null)
        );
        Loader::model("ResumeImport")->save($data, array(
            'id'    =>  $request->post('id', 0)
        ));
        return Result::wrap("修改成功", true, null);
    }

    public function sync(Request $request)
    {
        $token = $request->param('token');
        $list = Loader::model("ResumeImport")->getByToken($token);
        $dataList = array();
        foreach ($list as $item) {
            if (!empty($item->name) && !empty($item->mobile) && !empty($item->email) && !empty($item->post)) {
                $sex = null;
                if($item->sex == '男') {
                    $sex = 1;
                } else if ($item->sex == '女') {
                    $sex = 2;
                }
                array_push($dataList, array(
                   'name'   =>  $item->name,
                   'mobile'   =>  $item->mobile,
                   'email'   =>  $item->email,
                   'post'   =>  $item->post,
                   'sex'   =>  $sex,
                   'resume_file_path'   =>  $item->resume_file_path,
                ));
            }
        }
        $insertNumber = Loader::model("resume")->batchImport($dataList);
        return Result::wrap("总共 ". count($list) ." 条，成功写入" . $insertNumber . "条", true, null);
    }

    public function single(Request $request)
    {
        $fileName = $this->upload($request);
        return Result::wrap("上传成功", true, str_replace(Config::get("upload_path") . DIRECTORY_SEPARATOR, '', $fileName));
    }

}
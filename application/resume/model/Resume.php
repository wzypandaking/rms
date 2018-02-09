<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/1/31
 * Time: 下午5:22
 */
namespace app\resume\model;

use app\common\model\Common;
use think\Config;
use think\Db;


class Resume extends Common
{

    public function _list($param, $start, $limit, $sortBy=array())
    {
        if ($start > 0) {
            $start -= 1;
        }
        return $this->where($param)->order($sortBy)->limit($start * $limit, $limit)->select();
    }

    public function total($param)
    {
        return $this->where($param)->count();
    }

    public function getById($id)
    {
        return $this->where(array(
            'id'    =>  $id
        ))->find();
    }

    public function book($resumeInfo)
    {
        $this->save(array(
            'status'    =>  1
        ),array(
            'id'    =>  $resumeInfo->id
        ));
    }

    public function offer($resumeInfo)
    {
        $this->save(array(
            'status'    =>  2
        ),array(
            'id'    =>  $resumeInfo->id
        ));
    }

    public function eliminate($resumeInfo)
    {
        $this->save(array(
            'status'    => 3
        ),array(
            'id'    =>  $resumeInfo->id
        ));
    }

    public function batchImport($dataList)
    {
        $mobile = array();
        $mobileDataMap = array();
        foreach ($dataList as $data) {
            $mobileDataMap[$data['mobile']] = $data;
            array_push($mobile, $data['mobile']);
        }
        $existList = $this->where(array(
            'mobile'    =>  array('in', $mobile)
        ))->select();

        foreach ($existList as $exist) {
            unset($mobileDataMap[$exist->mobile]);
        }
        if (empty($mobileDataMap)) {
            return 0;
        }
        $newDataList = array_values($mobileDataMap);
        $this->insertAll($newDataList);
        return count($newDataList);
    }

    public function getByIds($resumeIdList)
    {
        return $this->where(array(
            'id'    =>  array('in', $resumeIdList)
        ))->select();
    }
}
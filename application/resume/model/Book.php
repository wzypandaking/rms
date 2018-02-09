<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/1
 * Time: 下午3:33
 */

namespace app\resume\model;


use app\common\model\Common;

class Book extends Common
{

    protected $table = 'rms_book_interview';

    public function _list($param, $start, $limit, $sortBy=array())
    {
        // TODO: Implement _list() method.
    }

    public function total($param)
    {
        // TODO: Implement total() method.
    }

    public function book($resumeInfo, $interviewInfo, $bookTime) {
        $data = array(
            'interview_id'  =>  $interviewInfo->id,
            'resume_id'     =>  $resumeInfo->id,
            'book_time'     =>  strtotime($bookTime),
            'type'          =>  0,
        );
        $this->insert($data);
    }

}
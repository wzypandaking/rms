<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/5
 * Time: 下午7:48
 */

namespace app\utils;


use PHPExcel;
use PHPExcel_IOFactory;
use think\Config;

class Excel
{

    private static $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
'P','Q','R','S','T','U','V','W','X','Y','Z', 'AA','AB','AC','AD','AE',
'AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT',
'AU','AV','AW','AX','AY','AZ');

    public static function getDataListWithFormat($excelFile, $arrayKeys = array())
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($excelFile);
        $objectWorkSheet = $objPHPExcel->getActiveSheet();
        $rowIterator = $objectWorkSheet->getRowIterator();

        $dataList = array();
        while ($rowIterator->valid()) {
            $rowIndex = $rowIterator->key();
            $arrayValues = array();
            $columnIterator = $objectWorkSheet->getColumnIterator();
            while ($columnIterator->valid()) {
                $columnIndex = $columnIterator->key();
                $value = $objectWorkSheet->getCellByColumnAndRow(\PHPExcel_Cell::columnIndexFromString($columnIndex) - 1, $rowIndex)->getValue();
                array_push($arrayValues, $value);
                $columnIterator->next();
            }
            array_push($dataList, array_combine($arrayKeys, $arrayValues));
            $rowIterator->next();
        }
        return $dataList;
    }

    public static function getDataListWithoutFormat($excelFile)
    {
        $objPHPExcel = \PHPExcel_IOFactory::load($excelFile);
        $objectWorkSheet = $objPHPExcel->getActiveSheet();
        $rowIterator = $objectWorkSheet->getRowIterator();

        $dataList = array();
        while ($rowIterator->valid()) {
            $rowIndex = $rowIterator->key();
            $arrayValues = array();
            $columnIterator = $objectWorkSheet->getColumnIterator();
            while ($columnIterator->valid()) {
                $columnIndex = $columnIterator->key();
                $value = $objectWorkSheet->getCellByColumnAndRow(\PHPExcel_Cell::columnIndexFromString($columnIndex) - 1, $rowIndex)->getValue();
                array_push($arrayValues, $value);
                $columnIterator->next();
            }
            array_push($dataList, $arrayValues);
            $rowIterator->next();
        }
        return $dataList;
    }

    /**
     *
     * 导出excel
     *
     * @param $filename
     * @param $header
     * @param $dataList
     * @return string
     */
    public static function dump($filename, $header, $dataList)
    {
        $filename = Config::get("dump_path") . '/' . $filename;
        $dirname = dirname($filename);
        ! is_dir($dirname) && mkdir($dirname, 0777, true);

        $excel = new PHPExcel();
        self::buildHeader($excel, $header);
        self::buildBody($excel, $header, $dataList);

        $excel->getActiveSheet()->setTitle("sheet1");

        $writer = PHPExcel_IOFactory::createWriter($excel, "Excel2007");
        $writer->save($filename);
        return $filename;
    }

    /**
     * @param PHPExcel $excel
     * @param $header
     */
    private static function buildHeader($excel, $header)
    {
        $values = array_values($header);
        $cellNum = count($header);
        for($i=0; $i < $cellNum; $i ++){
            $excel->getActiveSheet()->setCellValue(Excel::$cellName[$i].'1', $values[$i]);
        }
    }

    /**
     * @param PHPExcel $excel
     * @param $header
     * @param $dataList
     */
    private static function buildBody($excel, $header, $dataList)
    {
        $cellNum = count($header);
        $rowNum = count($dataList);

        $keys = array_keys($header);
        for($i=0; $i < $rowNum; $i ++){
            for($j = 0; $j < $cellNum; $j ++){
                $excel->getActiveSheet(0)->setCellValue(Excel::$cellName[$j].($i+2), $dataList[$i][$keys[$j]]);
            }
        }
    }
}
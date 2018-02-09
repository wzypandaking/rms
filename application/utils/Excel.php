<?php
/**
 * Created by PhpStorm.
 * User: pandaking
 * Date: 2018/2/5
 * Time: 下午7:48
 */

namespace app\utils;


class Excel
{

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
}
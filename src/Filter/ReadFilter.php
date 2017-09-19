<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 10:32
 */
namespace Filter;
use PHPExcel_Reader_IReadFilter;
class ReadFilter implements PHPExcel_Reader_IReadFilter
{
    private $startRow;
    private $endRow;
    private $column;
    public function __construct($startRow,$endRow,array $column)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
        $this->column = $column;
    }

    public function readCell($column,$row,$sheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow) {
            if (in_array($column,$this->column)) {
                return true;
            }
        }
        return false;
    }
}
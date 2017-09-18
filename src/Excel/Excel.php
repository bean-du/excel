<?php 
namespace Excel;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
/**
* 
*/
class Excel 
{
    // 当前excel的sheet的总数
    public $sheetNumber = '';
    // 当前excel的sheet名称数组
    public $sheetNames = [];
    // 根据传入的文件类型，生产对应的reader
    private $reader;
    // 整个表
    public $tables;
    // 传入的文件名称
    public $fileName;
    // 返回读取的sheet数据
    public $sheetData;
    // 获取列总数
    public $column;
    // 获取行总数
    public $row;
    // 初始化文件名称
    public function __construct($path)
    {
        $this->fileName = $path;
    }

    /**
     * 读取整个excel
     * @return $this|bool
     */
    public function readExcel()
    {
        $fileType = PHPExcel_IOFactory::identify($this->fileName);
        $inputFileType = ['Excel5','Excel2007','Excel2003XML','OOCalc','CSV'];
        if (in_array($fileType,$inputFileType)){
            $this->reader = PHPExcel_IOFactory::createReader($fileType);
            $this->tables = $this->reader->load($this->fileName);
            $this->sheetNumber = $this->tables->getSheetCount();
            $this->sheetNames = $this->tables->getSheetNames();
            return $this;
        }else{
            return false;
        }
	}

    /**
     * 读取一个表格
     * @param $sheet
     * @return mixed
     */
    public function readSheet($sheet)
    {
        $this->sheetData = $this->tables->getSheetByName($sheet);
        $this->column = PHPExcel_Cell::columnIndexFromString($this->sheetData->getHighestColumn());
        $this->row = $this->sheetData->getHighestRow();
        return $this;
	}

    public function getData()
    {
        $data = [];
        for ($row = 2; $row <= $this->row; $row++){
            for ($column = 0; $column <= $this->column; $column++){
                $column = PHPExcel_Cell::stringFromColumnIndex($column);
                $data[] = $this->sheetData->getCell($column,$row)->getValue();
            }
        }
        return $data;
	}
}

<?php 
namespace Excel;
use PHPExcel;
use PHPExcel_IOFactory;
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
    // 传入的文件名称
    public $fileName;
    // 返回读取的sheet数据
    public $sheetData;
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
            $tables = $this->reader->load($this->fileName);
            $this->sheetNames = $tables->getSheetNames();
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
        $this->sheetData = $this->reader->setLoadSheetsOnly($sheet);
        return $this;
	}

    public function readColumns()
    {
	}
}

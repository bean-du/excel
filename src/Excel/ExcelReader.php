<?php 
namespace Excel;
use Filter\ReadFilter;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_RichText;
use PHPExcel_Shared_Date;
use PHPExcel_Reader_Exception;
use PHPExcel_Cell_DataType;
/**
* 
*/
define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
class ExcelReader
{
    // 传入的文件名称
    private $fileName;
    // 过滤器，如果想指定读取行和列，可以使用过滤器
    public $filterSubset;
    // 当前excel的sheet的总数
    public $sheetNumber = '';
    // 当前excel的sheet名称数组
    public $sheetNames = [];
    // 根据传入的文件类型，生产对应的reader
    private $reader;
    // 保存整个表
    private $tables;
    // 返回读取的sheet数据
    private $sheetData;
    // 获取列总数
    public $column;
    // 获取行总数
    public $row;
    // 获取sheet的key值
    public $dataKeys = [];
    // 获取一个sheet中的所有数据
    public $data = [];
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
//        var_dump($this->fileName);exit;
        // 获取传入文件的类型
        $fileType = PHPExcel_IOFactory::identify(BASE_PATH.$this->fileName);
        // 设置允许的文件类型
        $inputFileType = ['Excel5','Excel2007','Excel2003XML','OOCalc','CSV'];
        // 判断是否为允许的类型，是不允许的类型直接return  false
        if (in_array($fileType,$inputFileType)){
            // 根据文件类型创建不同的Reader
            $this->reader = PHPExcel_IOFactory::createReader($fileType);
            // 是否有数据过滤器
            if ($this->filterSubset){
                $this->reader->setReadFilter($this->filterSubset);
            }
            // 加载整个表格
            try {
                $this->tables = $this->reader->load(BASE_PATH.$this->fileName);
            }catch (PHPExcel_Reader_Exception $e){
                die('Error loading file "'.pathinfo(BASE_PATH.$this->fileName).'": '.$e->getMessage());
            }
            // 获取表格的sheet总数
            $this->sheetNumber = $this->tables->getSheetCount();
            // 获取表格所有的sheet的名称，返回数组
            $this->sheetNames = $this->tables->getSheetNames();

            return $this;
        }else{
            return false;
        }
	}

    /**
     * 读取一个表格
     * @param $sheetName
     * @return mixed
     */
    public function readSheet($sheetName = 'Active')
    {
        // 默认读取激活的sheet，如果有传入sheet名称，读取指定的sheet
        $sheetName == 'Active' ? $this->sheetData = $this->tables->getActiveSheet() : $this->sheetData = $this->tables->getSheetByName($sheetName);
        // 计算总列数
        $this->column = PHPExcel_Cell::columnIndexFromString($this->sheetData->getHighestColumn());
        // 读取总行数
        $this->row = $this->sheetData->getHighestRow();

        return $this;
	}

    /**
     * 获取当前sheet的数据key
     * @return $this
     */
    public function getKey()
    {
        for($i = 0; $i < $this->column; $i++){
            // 将列的字母转化为索引
            $cellName = PHPExcel_Cell::stringFromColumnIndex($i).'1';
            //取得列内容
            $cellVal = $this->sheetData->getCell($cellName)->getValue();
            if( !$cellVal ){
                break;
            }
            $this->dataKeys[]= $cellVal;
        }
        return $this;
    }
    /**
     * 获取sheet中的数据
     * @return array
     */
    public function getData()
    {
        $data = [];
        for( $i = 1; $i <= $this->row ;$i++ ){//ignore row 1
            $row = array();
            for( $j = 0; $j <= $this->column;$j++ ){
                // 将列的字母转化为索引
                $cellName = PHPExcel_Cell::stringFromColumnIndex($j).$i;
                // 获取phpExcel 实例
                $cell = $this->sheetData->getCell($cellName);
                // 获取内容
                $cellVal = $cell->getValue();
                // 判断是否为公式
                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA){
                    // 计算公式值
                    $cellVal = $cell->getCalculatedValue();
                }
                // 判断是否为Excel日期格式
                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_NUMERIC){
                    if (PHPExcel_Shared_Date::isDateTime($cell)){
                        $cellVal = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cellVal));
                    }
                }
                // 富文本转换字符串
                if($cellVal instanceof PHPExcel_RichText){
                    $cellVal = $cellVal->__toString();
                }
                $cellVal = trim(mb_ereg_replace('(([\r\n\t\s])*(　)*)*$', '', $cellVal));
                $cellVal = trim(mb_ereg_replace('^(([\r\n\t\s])*(　)*)*', '', $cellVal));
                $row[] = $cellVal;
            }
            $data[] = $row;
        }
        return $data;
	}
    /**
     * 添加数据过滤
     * @param $startRow
     * @param $endRow
     * @param array $columns
     * @return $this
     */
    public function specifiedData($startRow,$endRow,array $columns)
    {
        $this->filterSubset = new ReadFilter($startRow,$endRow,range($columns[0],$columns[1]));
        return $this;
    }
    /*
     * excel日期转化
     * excel中日期读取出来是个数字，需要转化
     **/
    public function excelTime($date){
        $date = date("Y-m-d",PHPExcel_Shared_Date::ExcelToPHP($date) );
        return  $date;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Acorn
 * Date: 2017/9/19
 * Time: 10:13
 * Email: 343125118@qq.com
 */

namespace Excel;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Writer_CSV;
use PHPExcel_Writer_Excel5;
use PHPExcel_Cell;
class ExcelWriter
{
    private $excel;
    private $writer;
    public function __construct()
    {
        $this->excel = new PHPExcel();
    }

    /**
     * 设置表基本信息
     * @param array $baseInfo
     * @return $this
     */
    public function setBase(array $baseInfo)
    {
        $this->excel->getProperties()->setCreator($baseInfo['creator']);
        $this->excel->getProperties()->setTitle($baseInfo['title']);
        $this->excel->getProperties()->setSubject($baseInfo['subject']);
        $this->excel->getProperties()->setKeywords($baseInfo['keywords']);
        $this->excel->getProperties()->setDescription($baseInfo['description']);
        $this->excel->getProperties()->setCategory($baseInfo['type']);
        return $this;
    }

    /**
     * 设置字体颜色
     * @param array $columns
     * @param $size
     * @return $this
     */
    public function setFontSize(array $columns,$size)
    {
        if ($columns){
            for ($i = 0; $i < count($columns); $i++){
                $this->excel->getActiveSheet()->getStyle($columns[$i])->getFont()->setSize($size);
            }
        }
        return $this;
    }

    /**
     * 设置单元格字体颜色
     * @param array $columns
     * @param $color
     * @return $this
     */
    public function setFontColor(array $columns , $color)
    {
        if ($columns){
            for ($i = 0; $i < count($columns); $i++){
                $this->excel->getActiveSheet()->getStyle($columns[$i])->getFont()->setColor($color);
            }
        }
        return $this;
    }

    /**
     * 设置单元格的对其方式
     * @param array $columns
     * @param string $horizontal 参数是大写 “RIGHT","LEFT","CENTER"
     * @param string $vertical 参数是大写 “BOTTOM","TOP","CENTER"
     * @return $this
     */
    public function setAlignment(array $columns , $horizontal = '' , $vertical = '')
    {
        if ($horizontal != ''){
            if ($columns){
                for ($i = 0; $i < count($columns); $i++){
                    switch ($horizontal){
                        case 'RIGHT' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                            break;
                        case 'LEFT' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            break;
                        case 'CENTER' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            break;
                    }

                }
            }
        }
        if ($vertical != ''){
            if ($columns){
                for ($i = 0; $i < count($columns); $i++){
                    switch ($vertical){
                        case 'BOTTOM' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
                            break;
                        case 'TOP' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                            break;
                        case 'CENTER' :
                            $this->excel->getActiveSheet()->getStyle($columns[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                            break;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 设置单元格背景颜色
     * @param array $columns
     * @param $color
     * @return $this
     */
    public function setBackgroundColor(array $columns,$color)
    {
        for ($i = 0; $i < count($columns); $i++){
            $this->excel->getActiveSheet()->getStyle($columns[$i])->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $this->excel->getActiveSheet()->getStyle($columns[$i])->getFill()->getStartColor()->setARGB($color);
        }
        return $this;
    }

    /**
     * 设置单元格宽度，如果不指定宽度，将自动适应宽度
     * @param $columns
     * @param string $size
     * @return $this
     */
    public function setWidth(array $columns,$size = '')
    {
        if ($columns){
            if ($size != ''){
                for ($i = 0; $i < count($columns); $i++){
                    $this->excel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth($size);
                }
            }else{
                for ($i = 0; $i < count($columns); $i++){
                    $this->excel->getActiveSheet()->getColumnDimension($columns[$i])->setAutoSize(true);
                }
            }
        }
        return $this;
    }

    /**
     * 合并单元格
     * @param $start
     * @param $end
     * @return $this
     */
    public function mergeCells($start,$end)
    {
        $this->excel->getActiveSheet()->mergeCells($start.':'.$end);
        return $this;
    }

    /**
     * 设置当前激活的sheet
     * @param $index
     * @return $this
     */
    public function setActiveSheet($index)
    {
        $this->excel->setActiveSheetIndex($index);
        return $this;
    }

    /**
     * 设置当前激活的sheet的名称
     * @param $name
     * @return $this
     */
    public function setActiveSheetName($name)
    {
        $this->excel->getActiveSheet()->setTitle($name);
        return $this;
    }

    /**
     * 设置表格的数据
     * @param $data
     * @return $this
     */
    public function setValue($data)
    {
        foreach ($data as $k => $v){
            $end = count($v);
            $column = range('A',PHPExcel_Cell::stringFromColumnIndex($end-1));
            for ($i = 0; $i < $end; $i++){
                $this->excel->getActiveSheet()->setCellValue($column[$i].($k+1),$data[$k][$i]);
            }
        }
        return $this;
    }

    /**
     * 设置导出格式,目前支持 'xlsx','csv','xls'
     * @param $format 'xlsx','csv','xls'
     * @return $this
     */
    public function setFormat($format)
    {
        switch ($format){
            case 'xlsx' :
                $this->writer = new PHPExcel_Writer_Excel2007();
                break;
            case 'csv' :
                $this->writer = new PHPExcel_Writer_CSV($this->excel);
                break;
            case 'xls' :
                $this->writer = new PHPExcel_Writer_Excel5($this->excel);
        }
        return $this;
    }

    /**
     * @param $fileName
     * @param $path
     * @param $isSave
     */
    public function outPut($fileName,$isSave = false,$path = 'php://output')
    {
        if (!$isSave){
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");
            //文件名称
            header('Content-Disposition:attachment;filename='.$fileName);
            header("Content-Transfer-Encoding:binary");
        }
        $this->writer->save($path);

    }
}
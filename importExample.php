<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require 'vendor/autoload.php';
//use Excel\Excel;
use Excel\ExcelReader;
//use Export\Export;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 12:47
 */
class importExample
{
    public function import()
    {
        $file = $_FILES;
        if ($file['file']['tmp_name']){
            $excel = new ExcelReader($file['file']['tmp_name']);
            $res = $excel->readExcel()->readSheet()->getData();
            var_dump($res);
        }else{
            echo '文件上传失败';
        }
    }
}
$i = new importExample();
$i->import();
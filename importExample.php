<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require 'vendor/autoload.php';
//use Excel\Excel;
use Excel\ExcelReader;
use Helpers\Helpers;
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

        $help = new Helpers();
        if ($help->hasFile('file')){
            $file = $help->uploadFile('file');
            if (!is_dir('/upload')){
                mkdir('/upload');
            }
            $fileName = rand(10000,99999).'.'.$file->fileExtension();
            file_put_contents($fileName,file_get_contents($file->filePath()));
            $excel = new ExcelReader('/'.$fileName);
            $res = $excel->readExcel()->readSheet()->getData();
            @unlink($fileName);
            var_dump($res);
        }else{
            echo '文件上传失败';
        }
    }
}
$i = new importExample();
$i->import();
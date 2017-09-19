<?php
/**
 * Created by PhpStorm.
 * User: Acorn
 * Date: 2017/9/19
 * Time: 10:13
 * Email: 343125118@qq.com
 */

namespace Import;
use Excel\ExcelReader;

class Import
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
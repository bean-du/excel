<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 12:49
 */

namespace Import;
use Excel\Excel;

class Import
{
    public function import()
    {
        $file = $_FILES;
        $excel = new Excel($file['file']['tmp_name']);
        $res = $excel->readExcel();
        $r = $res->readSheet($res->sheetNames[0])->getData();
        var_dump($r);
    }
}
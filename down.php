<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require 'vendor/autoload.php';
use Excel\ExcelWriter;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 14:01
 */

function index(){
    $writer = new ExcelWriter();

    for ($i = 0; $i < 100; $i++){
        for ($j = 0; $j < 10; $j++){
            $data[$i][$j] = 'test ['.$i.']+['.$j.']';
        }
    }
    $writer->setWidth(range('A','J'))
        ->setValue($data)
        ->setFormat('xls')
        ->setAlignment(array('A1','C1'),'CENTER')
        ->setBackgroundColor(array('A1','C1'),'#ccccc')
        ->setAlignment(array('A2','C2'),'RIGHT')
        ->setActiveSheetName('Bean')
        ->outPut('test.xls');
}
index();
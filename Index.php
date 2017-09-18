<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
require 'vendor/autoload.php';
//use Excel\Excel;
use Import\Import;
//use Export\Export;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 12:47
 */
class Index
{
    public function import()
    {
        $c = new Import();
        $c->import();
    }
}
//function import(){
//    $file = $_FILES;
//    var_dump($file);
//}
//import();
$i = new Index();
$i->import();
<?php
/**
 * Created by PhpStorm.
 * User: bean
 * Date: 9/21/17
 * Time: 20:42
 */

namespace Helpers;


class Helpers
{
    private static $file;
    private static $post;
    public function uploadFile($file)
    {
        self::$post = $file;
        if ($_FILES[$file]['error'] == 0){
            self::$file = $_FILES;
        }else{
            self::$file = false;
        }
        return $this;
    }

    public function hasFile($file)
    {
        return $_FILES[$file]['error'] == 0 ? true : false;
    }

    public function fileExtension(){
        return substr(self::$file[self::$post]['name'], strrpos(self::$file[self::$post]['name'], '.')+1);
    }

    public function fileName()
    {
        return self::$file[self::$post]['name'];
    }

    public function filePath()
    {
        return self::$file[self::$post]['tmp_name'];
    }

    public function fileSize()
    {
        return self::$file[self::$post]['size'];
    }

    public function fileMineType()
    {
        return self::$file[self::$post]['type'];
    }
}
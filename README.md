 #### bean/excel 
 > 这是一个Excel处理包，包含了ExcelReader和ExcelWriter、Helpers类，帮助你处理基本的Excel操作。
 
 `ExcelReander` 类包主要负责读取Excel，智能的识别文件类型和读取文件，让你不在为导入的文件类型而烦恼，目前支持的格式有：`xlsx` ,`xls`,`csv` 。也就是说目前支持excel97-excel2003，excel2007和csv格式。
 
 > 特性 :
 1. 自动判断传入文件类型
 2. 支持excel日期格式
 3. 支持公式计算值
 4. 支持自定义获取数据
 > 使用实例 :
 
 ```php
    use Excel/ExcelReader;
    // 实例化ExcelReader类
    $reader = new ExcelReader($realPath);
    // 粗暴的获取Excel数据
    $data = $reader->readExcel()->readSheet()->getData();
    // 也可以这样使用
    $excel = $reader->readExcel();
    $sheetNumber = $excel->sheetNumber;
    $sheetName = $excel->sheetName[0];
    $sheet = $excel->readSheet($sheetName);
    $columnCount = $sheet->column;
    $rowCount = $sheet->row;
    $data = $sheet->getData()
```

`ExcelWriter` 是Excel导出类

> 特性 :
1. 支持写入`xslx` `xls` `csv`格式
2. 支持设置字体颜色
3. 支持设置单元格背景颜色
4. 支持设置单元格宽度（指定和自适应）
5. 支持合并单元格
6. 支持设置单元格对齐方式（水平、垂直）
7. 支持多sheet操作
8. 支持保存文件和浏览器输出

> 使用实例 : 
```php
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
```

> 为了方便上传的同学，提供一个Helpers上传文件类。

> 使用实例 :

```php
    $help = new Helpers();
    if ($help->hasFile('file')){
        $file = $help->uploadFile('file');
        $fileExtension = $file->fileExtension();
        $fileName = $file->fileName();
        $fileSize = $file->fileSize();
        $fileMineType = $file->fileMineType();
    }else{
        echo '文件上传失败';
    }
```

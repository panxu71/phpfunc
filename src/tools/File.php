<?php
// +----------------------------------------------------------------------
// | panxu/php-func(文件处理工具类)  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.  
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ) 
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>  
// +----------------------------------------------------------------------
namespace func\tools;

use func\Str;

class File
{

    public $rootPath;

    public function __construct()
    {
        // __FILE__ 可以获取当前文件的绝对路径
        $this->rootPath = getcwd();
    }

    /**
     * 创建文件夹
     *
     * @param string $dir 文件夹名称
     * @return void
     */
    public function folder(string $dir = "")
    {
        $dir = $dir == "" ? $this->rootPath : $dir;
        return is_dir($dir) or mkdir(iconv("UTF-8", "GBK", $dir), 0777, true);
    }

    /**
     * 读取目录文件信息
     *
     * @param string $dir 文件夹名称
     * @return void
     */
    public function readFolder(string $dir): array
    {
        $files = ["Folder or file does not exist"];
        if (!is_dir($dir)) {
            return ["Folder or file does not exist"];
        }

        $iterator = new \FilesystemIterator($dir);
        while ($iterator->valid()) { // 检测迭代器是否到底了
            array_push($files, [
                'name'  => $iterator->getFilename(),
                'type'  => $iterator->getType() ?? "",
                "ctime" => $iterator->getCTime(),
                "mtime" => $iterator->getMTime(),
                "size"  => $iterator->getSize(),
                "ext"   => $iterator->getExtension(),
            ]);
            $iterator->next(); // 游标往后移动
        }
        return $files;
    }

    /**
     * 拷贝文件或目录
     *
     * @param string $file
     * @param string $dir
     * @return bool
     */
    public function copy(string $file = "", string $dir = ""): bool
    {
        if (!file_exists($file)) {
            return "Folder or file does not exist";
        }
        $dir = ($dir == "" ? $this->rootPath : $dir) . DIRECTORY_SEPARATOR;
        // 文件拷贝
        if (is_file($file)) {
            return copy($file, $dir . basename($file));
        }
        // 目录拷贝
        if (is_dir($file) && $dirHandle = @opendir($file)) {
            while ($filename = readdir($dirHandle)) {
                if ($filename != "." && $filename != "..") {
                    $subSrcFile = $file . "\\" . $filename;
                    $subToFile = $dir . "\\" . $filename;
                    is_dir($subSrcFile) && self::copy($subSrcFile, $subToFile);
                    is_file($subSrcFile) && copy($subSrcFile, $subToFile);
                }
            }
            closedir($dirHandle);
        }
        return true;
    }

    /**
     * 读取文本文件内容 (包括但不限于文本文件)
     * 
     * @param string $file 文件路径
     * @return string
     */
    public function readTextFile(string $file = ""): string
    {
        $fp = fopen($file, 'rb');
        $contents = '';
        while (!feof($fp)) {
            $contents .= fgets($fp); //逐行读取。如果fgets不写length参数，默认是读取1k。
        }
        return $contents;
    }
}

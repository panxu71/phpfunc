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
    /**
     * 获取文件资源路径
     *
     * @return string
     */
    public static function path(): string
    {
        $rootPath = PHP_OS == "WINNT" ? "public" . DIRECTORY_SEPARATOR : "";
        return getcwd() . DIRECTORY_SEPARATOR . $rootPath . "upload" . DIRECTORY_SEPARATOR;
    }

    /**
     * 创建文件夹
     *
     * @param string $dir 文件夹名称
     * @return string 文件夹路径
     */
    public static function folder(string $dir = ""): string
    {
        $dir = ($dir == "" ? self::path() : $dir . DIRECTORY_SEPARATOR)  . date("Ymd");
        is_dir($dir) or mkdir(iconv("UTF-8", "GBK", $dir), 0777, true);
        return $dir . DIRECTORY_SEPARATOR;
    }

    /**
     * 读取目录文件信息
     *
     * @param string $dir 文件夹名称
     * @return void
     */
    public static function readFolder(string $dir): array
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
    public static function copy(string $file = "", string $dir = ""): bool
    {
        if (!file_exists($file)) {
            return "Folder or file does not exist";
        }
        $dir = ($dir == "" ? self::folder() : $dir) . DIRECTORY_SEPARATOR;
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
    public static function readTextFile(string $file = ""): string
    {
        $fp = fopen($file, 'rb');
        $contents = '';
        while (!feof($fp)) {
            $contents .= fgets($fp); //逐行读取。如果fgets不写length参数，默认是读取1k。
        }
        return $contents;
    }

    /**
     * 创建并追加内容到文件
     *
     * @param string $fileName 文件名
     * @param string $content  内容
     * @return string  返回文件绝对路径
     */
    public static function write(string $fileName, string $content = ""): string
    {
        // 判断文件是否存在
        $file = fopen($fileName, file_exists($fileName) ? 'a' : "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);
        return realpath($fileName);
    }

    /**
     * 文件上传
     *
     * @param array $file   文件数组（$_FILES）
     * @param string $path  指定上传目录，不存在则新建
     * @param string $name  指定文件名
     * @return string
     */
    public static function upload(array $file, string $name = ""): string
    {
        $extension = pathinfo($file["file"]["name"])["extension"];
        $fullName  = $name != "" ? $name : self::name($extension);
        move_uploaded_file($file["file"]["tmp_name"], $fullName);
        return $fullName;
    }

    /**
     * 获取文件扩展名
     *
     * @param string $fileUri
     * @return string
     */
    public static function extension(string $fileUri): string
    {
        $parseData = parse_url($fileUri);
        if (!isset($parseData["path"])) {
            return false; //"文件错误"
        }
        $fileInfo  = pathinfo($parseData["path"]);
        if (!isset($fileInfo["extension"])) {
            return false; //文件扩展名错误
        }
        return $fileInfo["extension"];
    }

    /**
     * 生成文件名
     *
     * @param string $extension 文件扩展名
     * @param string $location  文件存储位置
     * @return string
     */
    public static function name(string $extension = "png", string $location = ""): string
    {
        return self::folder($location) . Str::uuid(false, false) . ".{$extension}";
    }
}

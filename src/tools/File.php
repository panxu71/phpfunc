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
    public static function path(string $location = ""): string
    {
        $rootPath = PHP_OS == "WINNT" ? "public" . DIRECTORY_SEPARATOR : "";
        return getcwd() . DIRECTORY_SEPARATOR . $rootPath . ($location != "" ? $location : "upload") . DIRECTORY_SEPARATOR;
    }

    /**
     * 创建文件夹
     *
     * @param string $dir 文件夹名称
     * @return string 文件夹路径
     */
    public static function folder(string $location = ""): string
    {
        $dir = self::path($location)  . date("Ymd");
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
     * 文件上传
     *
     * @param array $file   文件数组（$_FILES）
     * @param string $path  指定上传目录，不存在则新建
     * @param string $name  指定文件名
     * @return string
     */
    public static function upload(array $file, string $fileName = ""): string
    {
        $extension = pathinfo($file["file"]["name"])["extension"];
        move_uploaded_file($file["file"]["tmp_name"], self::name($fileName, $extension));
        return $fileName;
    }

    /**
     * 生成文件名
     *
     * @param string $fileName  指定文件名
     * @param string $extension 指定文件扩展名
     * @return string
     */
    public static function name(string $fileName = "", $ext = "png"): string
    {
        $strname          = Str::uuid(false, false);
        $truename         = "$strname.$ext";
        $dirname          = "upload";
        if ($fileName != "") {
            $pathinfo     = pathinfo($fileName);
            $dirname      = isset($pathinfo["dirname"]) && $pathinfo["dirname"] != "." ? $pathinfo["dirname"] : $dirname;
            $extension    = $pathinfo["extension"] ?? $ext;
            $truename     = isset($pathinfo["extension"]) && $pathinfo["extension"] != "" ? $pathinfo["basename"] : rtrim($pathinfo["basename"], '.') . "." . $extension;
            if (file_exists(self::folder($dirname) .  $truename)) {
                $truename = "$strname.$extension";
            }
        }
        return self::folder($dirname) . $truename;
    }

    /**
     * 解析远程图片
     *
     * @param string $imgUrl   图片路径
     * @return void
     */
    public static function parseImage(string $imgUrl = "")
    {
        header('Content-type: image/jpg');
        exit(Http::fileToBinaryData($imgUrl));
    }
}

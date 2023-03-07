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
        $rootPath = PHP_OS == "WINNT" ? "public" : "";
        return str_replace(["//", "\\"], DIRECTORY_SEPARATOR, getcwd() . DIRECTORY_SEPARATOR . $rootPath . DIRECTORY_SEPARATOR . ($location != "" ? $location : ""));
    }

    /**
     * 创建文件夹
     *
     * @param string $dir 文件夹名称
     * @return string 文件夹路径
     */
    public static function folder(string $location = ""): string
    {
        $dir = self::path($location);
        is_dir($dir) or mkdir(iconv("UTF-8", "GBK", $dir), 0777, true);
        return str_replace(["//", "\\"], DIRECTORY_SEPARATOR, $dir . DIRECTORY_SEPARATOR);
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
        $fileName  = self::name($fileName, $extension);
        move_uploaded_file($file["file"]["tmp_name"], $fileName);
        return $fileName;
    }

    /**
     * 创建并追加内容到文件
     *
     * @param string $fileName 文件名
     * @param string $content  内容
     * @return string  返回文件绝对路径
     */
    public static function write(string $content = "", string $name = "", bool $isrepeat = true, string $ext = "log"): string
    {
        $fileName = self::name($name, $ext, $isrepeat);
        $file     = fopen($fileName, file_exists($fileName) ? 'a' : "w") or die("Unable to open file!");
        fwrite($file, $content);
        fclose($file);
        return realpath($fileName);
    }

    /**
     * 生成文件名
     *
     * @param string $fileName  指定文件名
     * @param string $extension 指定文件扩展名
     * @param string $isrepeat  相同文件是否覆盖
     * @return string
     */
    public static function name(string $fileName = "", string $extension = "", bool $isrepeat = false): string
    {
        $rname         = Str::uuid(false, false);
        $dirname       = "upload" . DIRECTORY_SEPARATOR . date("Ymd") . DIRECTORY_SEPARATOR;
        $pathinfo      = pathinfo($fileName);
        isset($pathinfo["dirname"]) && $pathinfo["dirname"] != "." && $dirname .= $pathinfo["dirname"];
        $extension     = isset($pathinfo["extension"]) ? $pathinfo["extension"] : $extension;
        if ($extension == "") {
            return "文件扩展名不存在";
        }
        $filename      = isset($pathinfo["filename"]) && $pathinfo["filename"] != "" ? $pathinfo["filename"] : $rname;
        $fullname      = self::folder($dirname) . ($filename ?? $rname) . ".{$extension}";
        if (!$isrepeat && file_exists($fullname)) {
            $fullname  = self::folder($dirname) . "$rname.{$extension}";
        }
        return $fullname;
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
        exit(self::fileToBinaryData($imgUrl));
    }

    /**
     * 返回文件类型
     * 支持远程文件
     * @param string $uri 文件资源路径
     * @return array      文件类型数组
     */
    public static function contentType(string $uri = ""): array
    {
        if ($uri  == "") {
            return "";
        }
        // 获取远程文件扩展名
        if (preg_match("/^https|http/", $uri)) {
            $http = (new Http)->check($uri);
            if (isset($http["http_code"]) && $http["http_code"] == "200") {
                $data['type'] = $http["content_type"];
            }
        } else {
            // 区分绝对路径与相对路径
            $uri = (!file_exists($uri) ? self::path() : "") . $uri;
            if (!file_exists($uri)) {
                return [];
            }
            $uriInfo           = parse_url($uri);
            $data["extension"] = isset($uriInfo["path"]) ? (pathinfo($uriInfo["path"], PATHINFO_EXTENSION) ?? "") : "";
            $data["type"]      = mime_content_type($uri) ?? "";
        }

        if (!isset($data["type"]) || $data["type"] == "") {
            return [];
        }

        if (!isset($data["extension"]) || $data["extension"] == "") {
            if (strpos($data["type"], 'image/') !== false && $data["type"] != "gif") {
                $data["extension"] = "png";
            }
            if (strpos($data["type"], 'video/') !== false) {
                $data["extension"] = "mp4";
            }
        }
        return $data;
    }

    /**
     * 文件转二进制流
     *
     * @param string $file
     * @return string 
     */
    public static function fileToBinaryData(string $file): string
    {
        if (preg_match("/^https|http/", $file)) {
            $http = (new Http)->check($file);
            if (!isset($http["content_type"]) || $http["http_code"] != "200") {
                return ""; //请求失败
            }
            $content = Http::curl($file, null, "GET", ["Host:" . parse_url($file)['host']]);
        }
        if (!isset($content)) {
            if (!file_exists($file)) { // 判断文件是否存在
                return ""; //文件不存在
            }
            if ($fp = fopen($file, "rb", 0)) {
                $content = fread($fp, filesize($file));
                fclose($fp);
            }
        }
        return $content;
    }

    /**
     * 图片转base64(支持远程图片)
     *
     * @param string $image
     * @return string
     */
    public static function imgToBase64(string $content): string
    {
        return str_replace(PHP_EOL, '', chunk_split(base64_encode(self::fileToBinaryData($content))));
    }
}

<?php
// +----------------------------------------------------------------------
// | panxu/php-func(请求处理工具类)  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.  
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ) 
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>  
// +----------------------------------------------------------------------
namespace func\tools;

class Http
{
    private static $ch;
    /**
     * 初始化
     *
     * @param string $uri
     */
    public function __construct(string $uri = "")
    {
        self::$ch = curl_init($uri);
        //判断ssl连接方式
        if ($uri != "" && stripos($uri, 'https://') !== false) {
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt(self::$ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt(self::$ch, CURLOPT_ENCODING, '');
    }

    /**
     * 检测请求链接
     *
     * @param string $uri
     * @return boolean
     */
    public function check(string $uri): bool
    {
        new Http($uri);
        curl_setopt(self::$ch, CURLOPT_NOBODY, true); // 不取回数据
        if (curl_exec(self::$ch) !== false) { // 发送请求如果请求没有发送失败
            return curl_getinfo(self::$ch, CURLINFO_HTTP_CODE) == 200; // 再检查http响应码是否为200
        }
    }

    /**
     * 下载远程资源
     *
     * @param string $imgUrl    远程文件url
     * @param string $location  文件存储位置
     * @return string 返回文件路径
     * @return void
     */
    public static function wget(string $uri, string $location = ""): string
    {
        if (!(new self)->check($uri)) {
            return "请求失败";
        }
        curl_setopt(self::$ch, CURLOPT_NOBODY, false);
        // 检查资源
        $httpInfo  = curl_getinfo(self::$ch);
        if ($httpInfo["http_code"] != "200") {
            return "下载失败"; // "下载失败，请求耗时" . $httpInfo['total_time'] . '秒'
        }
        // 获取远程资源类型
        $extension     = pathinfo($uri, PATHINFO_EXTENSION);
        if ($extension == "" && isset($httpInfo["content_type"])) {
            strpos($httpInfo["content_type"], 'image/') !== false && $extension = "png";
            strpos($httpInfo["content_type"], 'vodeo/') !== false && $extension = "mp4";
        }
        $fileName = File::name($location != "" ? $location : $extension, $extension);
        $fp       = fopen($fileName, 'w+');
        curl_setopt(self::$ch, CURLOPT_FILE, $fp);
        curl_exec(self::$ch);
        curl_close(self::$ch);
        fclose($fp);
        return $fileName;
    }

    /**
     * curl请求
     *
     * @param string $url
     * @param array|null $data
     * @param string $method
     * @param array $headers
     * @return void
     */
    public static function curl(string $url, array|null $data = null, string $method = "GET", array $headers = [])
    {
        if (!(new self)->check($url)) {
            return "请求失败";
        }
        curl_setopt(self::$ch, CURLOPT_NOBODY, false);
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_HEADER, false); //不返回头部信息
        if (strtolower($method) != 'get') {
            curl_setopt(self::$ch, CURLOPT_POST, 1);
        }
        $data != null && curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($data));
        count($headers) && curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers); //请求头
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);  //结果是否显示出来，1不显示，0显示    
        $result = curl_exec(self::$ch);
        curl_close(self::$ch);
        return $result;
    }

    /**
     * 远程文件转二进制流
     *
     * @param string $fileUri
     * @return void
     */
    public static function fileToBinaryData(string $url = "")
    {
        if (!(new self)->check($url)) {
            return "请求失败";
        }
        if (!preg_match("/^https|http/", $url)) {
            return "远程文件不存在";
        }
        return self::curl($url, null, "GET", ["Host:" . parse_url($url)['host']]);
    }
}

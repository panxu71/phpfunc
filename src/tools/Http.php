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
     * @return array
     */
    public function check(string $uri, array $headers = []): array
    {
        new Http($uri);
        count($headers) && curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers); //请求头
        curl_setopt(self::$ch, CURLOPT_NOBODY, true); // 不取回数据
        if (curl_exec(self::$ch) !== false) { // 发送请求如果请求没有发送失败
            return curl_getinfo(self::$ch);
            return curl_getinfo(self::$ch, CURLINFO_HTTP_CODE) == 200; // 再检查http响应码是否为200
        }
    }

    /**
     * 下载远程资源
     *
     * @param string $imgUrl    远程文件url
     * @param string $location  文件存储位置
     * @return string 返回文件路径
     */
    public static function wget(string $uri, string $location = ""): string
    {
        $http = (new self)->check($uri);
        if (!isset($http["content_type"]) || $http["http_code"] != "200") {
            return ""; //请求失败
        }
        curl_setopt(self::$ch, CURLOPT_NOBODY, false);
        $fileName = File::name($location, File::contentType($uri)["extension"]);
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
    public static function curl(string $url, array|string|null $data = null, string $method = "GET", array $headers = [], bool $response = false)
    {
        new Http($url);
        curl_setopt(self::$ch, CURLOPT_NOBODY, false);
        switch ($method) {
            case "GET":
                if (is_array($data) && count($data)) {
                    $url .= (!preg_match("/\?/", $url) ? "?" : "&") . http_build_query($data);
                }
                curl_setopt(self::$ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt(self::$ch, CURLOPT_POST, 1);
                break;
            case "PUT":
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_HEADER, false); //不返回头部信息
        // 是否获取请求头
        if ($response) {
            curl_setopt(self::$ch, CURLOPT_HEADER, TRUE);
        }
        $data != null && curl_setopt(self::$ch, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data);
        count($headers) && curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers); //请求头
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);  //结果是否显示出来，1不显示，0显示    
        $result = curl_exec(self::$ch);
        curl_close(self::$ch);
        return $result;
    }
}

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
    /**
     * curl请求
     *
     * @param string $url
     * @param array $data
     * @param string $type
     * @param array $headers
     * @return void
     */
    public static function curl(string $url, string $type = 'GET', array $data = [], array $headers = [])
    {
        $ch = curl_init();
        //判断ssl连接方式
        if (stripos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        $connttime = 300;  //连接等待时间500毫秒
        $timeout   = 15000; //超时时间15秒

        $requestData = is_array($data) ? http_build_query($data) : ($data ?? '');

        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url . ($requestData ? "?$requestData" : ''));
        //设置头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 模拟用户浏览器
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.25 Mobile Safari/537.36');
        // 解决乱乱码
        curl_setopt($ch, CURLOPT_ENCODING, '');
        //http 1.1版本
        //curl_setopt ($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
        //连接等待时间
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $connttime);
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
        //设置HEADER头部信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, count($headers) ? $headers : ["Content-type: application/json"]);

        switch ($type) {
            case "GET":
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
                break;
        }

        $response = curl_exec($ch); //获得返回值
        // $status = curl_getinfo($ch);
        curl_close($ch);
        return $response;
    }
}

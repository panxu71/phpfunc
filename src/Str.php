<?php

namespace func;

class Str
{
    /**
     * 字符串截取，支持中文和其他编码
     * @param  [string]  $str   [字符串]
     * @param  integer $start   [起始位置]
     * @param  integer $length  [截取长度]
     * @param  string  $charset [字符串编码]
     * @param  boolean $suffix  [是否有省略号]
     * @return
     */
    static public function msubstr($str, $start = 0, $length = 15, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            return mb_substr($str, $start, $length, $charset);
        }

        if (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }

        $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        return $suffix ? $slice . "…" : $slice;
    }

    /**
     * 获取随机字符串
     * @param  integer $length  字符串长度
     * @param  boolean $isupper 是否包含小写字母
     * @param  boolean $islower 是否包含大写字母
     * @param  boolean $isspec  是否包含特殊字符
     * @return string
     */
    static public function randomString($length = 6, $isupper = true, $islower = false, $isspec = false)
    {
        $numbers     = "0,1,2,3,4,5,6,7,8,9";
        $upperLetter = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
        $lowerLetter = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        $specialChars = "!,@,#,$,?,|,{,/,:,;,%,^,&,*,(,),-,_,[,],},<,>,~,+,=";
        $isupper     && $numbers .= $upperLetter;
        $islower     && $numbers .= $lowerLetter;
        $isspec      && $numbers .= $specialChars;
        $numbers     = explode(',', $numbers);
        shuffle($numbers); //打乱数组顺序
        $length      = $length > count($numbers) ? count($numbers) : $length;
        $string      = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $numbers[mt_rand(0, count($numbers) - 1)]; //随机取出一位
        }
        return $string;
    }
}

<?php
// +----------------------------------------------------------------------
// | panxu/php-func(工具类-日期处理)  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.  
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ) 
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>  
// +----------------------------------------------------------------------
namespace func\tools;

class Date
{
    protected static $timeStart = "00:00:00";
    protected static $timeEnd = "23:59:59";

    /**
     * 返回指定年份开始和结束日期 
     *
     * @param string $year 指定年份，默认本年度
     * @param boolean $isTimestamp 是否时间戳，默认日期格式
     * @return array
     */
    public static function year(string $year = "", bool $isTimestamp = false): array
    {
        $time          = time();
        $year          = $year != "" ? $year : date("Y", $time);
        $start         = date('Y-m-d', strtotime($year . "-1-1")) . " " . self::$timeStart; //本年开始
        $end           = date('Y-m-d', strtotime($year . "-12-31")) . " " . self::$timeEnd; //本年结束
        $data['start'] = $isTimestamp ? strtotime($start) : $start;
        $data['end']   = $isTimestamp ? strtotime($end) : $end;
        return $data;
    }
}

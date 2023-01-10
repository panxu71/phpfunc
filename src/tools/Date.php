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
    protected static $timeEnd   = "23:59:59";

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

    /**
     * 返回指定季度开始和结束时间
     *
     * @param integer $season 指定季度，默认当前季度，范围（1-4）
     * @param integer $year   指定年份，默认当前年份
     * @param boolean $isTimestamp 是否时间戳，默认日期格式
     * @return array
     */
    public static function quarter(int $season = 0, int $year = 0, bool $isTimestamp = false): array
    {
        $season        = !$season ? ceil((date('n')) / 3) : $season; //获取季度
        $start         = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, (int)($year ? $year : date("Y"))); //季度开始
        $end           = strtotime("+3 month", $start) - 1; //季度结束
        $data['start'] = $isTimestamp ? $start : date('Y-m-d H:i:s', $start);
        $data['end']   = $isTimestamp ? $end : date('Y-m-d H:i:s', $end);
        return $data;
    }
}

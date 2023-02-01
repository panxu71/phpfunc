<?php
// +----------------------------------------------------------------------
// | panxu/php-func(日期处理工具类)  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.  
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ) 
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>  
// +----------------------------------------------------------------------
namespace func\tools;

use func\Str;

class Date
{
    /**
     * 返回指定年份开始和结束日期 
     *
     * @param int $year 指定年份，默认本年度
     * @param boolean $isTimestamp 是否时间戳，默认日期格式
     * @return array
     */
    public static function year(int $year = 0, bool $isTimestamp = false): array
    {
        $start         = mktime(0, 0, 0, 1, 1, (int)($year ? $year : date("Y"))); //年度开始
        $end           = strtotime("+1 year", $start) - 1; //年度开始
        $data['start'] = $isTimestamp ? $start : date('Y-m-d H:i:s', $start);
        $data['end']   = $isTimestamp ? $end : date('Y-m-d H:i:s', $end);
        return $data;
    }

    /**
     * 返回指定年份指定月份开始和结束日期
     *
     * @param integer $month 指定月份，默认当前月份，范围（1-12）
     * @param integer $year 指定年份，默认本年度
     * @param boolean $isTimestamp
     * @return void
     */
    public static function month(int $month = 0, int $year = 0, bool $isTimestamp = false): array
    {
        $year          = intval($year ?: date("Y"));
        $month         = intval($month || $month < 1 || $month > 12 ?: date("m"));
        $start         = mktime(0, 0, 0, $month, 1, $year);
        $end           = strtotime("+1 month", $start) - 1;
        $data['start'] = $isTimestamp ? $start : date('Y-m-d H:i:s', $start);
        $data['end']   = $isTimestamp ? $end : date('Y-m-d H:i:s', $end);
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
        $year          = intval($year ?: date("Y"));
        $season        = intval($season || $season < 1 || $season > 4 ?: ceil((date('n')) / 3));
        $start         = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, (int)($year ? $year : date("Y"))); //季度开始
        $end           = strtotime("+3 month", $start) - 1; //季度结束
        $data['start'] = $isTimestamp ? $start : date('Y-m-d H:i:s', $start);
        $data['end']   = $isTimestamp ? $end : date('Y-m-d H:i:s', $end);
        return $data;
    }

    /**
     * 返回指定年份指定周开始和结束时间
     *
     * @param integer $week 指定周，默认当前
     * @param integer $year 指定年，默认本年
     * @param boolean $isTimestamp 是否时间戳，默认日期格式
     * @return void
     */
    public static function week(int $week = 0, int $year = 0, bool $isTimestamp = false)
    {
        $year = intval($year ?: date("Y"));
        $week = intval($week ?: date("W"));
        ($week > date("W", mktime(0, 0, 0, 12, 28, $year)) || $week <= 0) && $week = 1;
        $week < 10 && $week = '0' . $week; // 注意：一定要转为 2位数，否则计算出错
        $start         = strtotime($year . 'W' . $week);
        $end           = strtotime("+1 week", $start) - 1;
        $data['start'] = $isTimestamp ? $start : date('Y-m-d H:i:s', $start);
        $data['end']   = $isTimestamp ? $end : date('Y-m-d H:i:s', $end);
        return $data;
    }

    /**
     * 时间格式化
     *
     * @param string|integer $date 时间(支持任意格式日期)
     * @return string
     */
    public static function format(string|int $date): string
    {
        // 检验传入数组时间范围是否合法
        // /(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})-(((0[13578]|1[02])-(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)-(0[1-9]|[12][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[13579][26])00))-02-29)/
        $newDate  = Str::stringToArray(date("YmdHis"));
        $date     = Str::stringToArray($date);
        $numCount = 0;
        foreach ($date as $v) {
            if (is_numeric($v)) {
                // $numCount == 0  && $v > 2 && $v = 2;
                $numCount == 4  && $v > 1 && $v = 0;
                $numCount == 6  && $v > 3 && $v = 1;
                $numCount == 8  && $v > 2 && $v = 0;
                $numCount == 10 && $v > 5 && $v = 5;
                $numCount == 12 && $v > 5 && $v = 5;
                $newDate[$numCount] = $v;
                $numCount++;
            }
        }
        return date("Y-m-d H:i:s", strtotime(implode("", $newDate)));
    }
}

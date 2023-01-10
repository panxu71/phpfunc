phpfunc
====

[![License](https://img.shields.io/packagist/l/panxu/phpfunc.svg?style=flat-square)](LICENSE)
[![Php Version](https://img.shields.io/badge/php-%3E=7.3-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/panxu/phpfunc)
[![Packagist Version](https://img.shields.io/packagist/v/panxu/phpfunc)](https://packagist.org/packages/panxu/phpfunc)
[![star](https://gitee.com/panxu11/phpfunc/badge/star.svg?theme=gray)](https://gitee.com/panxu11/phpfunc/stargazers)
[![fork](https://gitee.com/panxu11/phpfunc/badge/fork.svg?theme=dark)](https://gitee.com/panxu11/phpfunc/members)

一个简洁小巧且功能完善的PHP常用工具集。

### 安装教程

```shell
# 安装最新版本
composer require panxu/phpfunc
# 安装指定版本
composer require panxu/phpfunc:0.1.1
# 更新当前版本
composer update panxu/phpfunc
# 卸载
composer remove panxu/phpfunc
```

### 常用函数

|类名        |处理类型  |  功能说明  |
| -----   | ----- | ----  |
| Str      | 字符串 | 提供常用字符串快捷方法     |

#### 字符串函数

```php
use func\Str;

// 字符串截取，支持中文和其他编码
msubstr($str, $start = 0, $length = 15, $charset = "utf-8", $suffix = true)
// 使用案例：
Str::msubstr("phpfunc是一个简洁小巧且功能完善的PHP常用工具集",0,7); // phpfunc

// 返回随机字符串
randomString($length = 6, $isupper = true, $islower = false, $isspec = false)
// 使用案例：
Str::randomString(10); // iffpndyh2t

// 返回全局唯一UUID
uuid(bool $isConnector = true, bool $isMark = true, bool $islower = false)
// 使用案例：
Str::uuid();// {b86cc5ed-0736-4d7a-b5f6-e0aa52d3a5df}

//中文转拼音 (utf8版,gbk转utf8也可用)
pinyin($str, $ret_format = 'all', $placeholder = '_', $allow_chars = '/[a-zA-Z\d ]/')
// 使用案例：
Str::pinyin("我是中国人"); // wo shi zhong guo ren

// 生成某个范围内的随机姓名
randomName($sex = 0, $issurname = null, $iscompound = true)
// 使用案例：
Str::randomName();
// Array
// (
//     [surname] => 聂
//     [name] => 辰
//     [username] => 聂辰
// )

//随机邮箱 支持邮箱类型[126,163,139,189,qq,wo,sina,sohu,aliyun,foxmail,outlook]
randomEmail($type = "", $len = 6)
// 使用案例：
Str::randomEmail("sohu"); // 4wxbno@sohu.com

// 生成某个范围内的随机时间 
randomDate($begin = "", $end = "", $now = true)
// 使用案例：
Str::randomDate(); // 2023-01-10 11:57:23

// 生成随机手机号码 (默认随机;0,移动;1,联通;2,电信)
randomPhone($operator = null)
// 使用案例：
Str::randomPhone(); // 18952373556

// 生成随机身份证号（可验证）
randomCardId()
// 使用案例：
Str::randomCardId(); // 340421200511098459

// 随机金额或小数
randomFloat($num = 2, $min = 0, $max = 10)
// 使用案例：
Str::randomFloat(); // 9.18

// 返回随机省份或列表（中国共计34个省级行政区，包括23个省、5个自治区、4个直辖市、2个特别行政区）
randomProvince(bool $isIndex = false, bool $isAll = false)
// 使用案例：
Str::randomProvince();
// Array
// (
//     [index] => 14
//     [province] => 山东省
// )

```

### 常用工具类

|类名         |处理类型  |所属工具类 |  功能说明  |
| :-----:   |  :-----:  | :----:  | ----  |
| Date      |日期处理  |  tools |   常用日期处理方法     |

#### 日期类工具（Date）

```php
use func\tools\Date;

// 返回指定年份开始和结束日期 
year(int $year = 0, bool $isTimestamp = false)
// 使用案例：
Date::year();
Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-12-31 23:59:59
// )

// 返回指定季度开始和结束时间
quarter(int $season = 0, int $year = 0, bool $isTimestamp = false)
// 使用案例：
Date::quarter();
// Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-03-31 23:59:59
// )

// 返回指定年份指定月份开始和结束日期
month(int $month = 0, int $year = 0, bool $isTimestamp = false)
// 使用案例：
Date::month();
// Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-01-31 23:59:59
// )

// 返回指定年份指定周开始和结束时间
week(int $week = 0, int $year = 0, bool $isTimestamp = false)
// 使用案例：
Date::week();
// Array
// (
//     [start] => 2023-01-09 00:00:00
//     [end] => 2023-01-15 23:59:59
// )

```

### 版本更新

|版本 |日期 |说明  |
|:----:   | :----: | ----  |
| 0.1.1      | 2023-01-09 | 新增工具类tools     |
| 0.1.0      | 2023-01-07 | 发布第一个版本     |

### 最新记录

[![panxu/phpfunc](https://gitee.com/panxu11/phpfunc/widgets/widget_card.svg?colors=ffffff,1e252b,323d47,455059,d7deea,99a0ae)](https://gitee.com/panxu11/phpfunc)

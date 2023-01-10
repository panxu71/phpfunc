# phpfunc

[![License](https://img.shields.io/packagist/l/panxu/phpfunc.svg?style=flat-square)](LICENSE)
[![Php Version](https://img.shields.io/badge/php-%3E=7.3-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/panxu/phpfunc)
[![Packagist Version](https://img.shields.io/packagist/v/panxu/phpfunc)](https://packagist.org/packages/panxu/phpfunc)

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
```

### 常用工具类

|类名         |处理类型  |所属工具类 |  功能说明  |
| :-----:   |  :-----:  | :----:  | ----  |
| Date      |日期处理  |  tools |   常用日期处理方法     |

### 版本更新

|版本 |日期 |说明  |
|:----:   | :----: | ----  |
| 0.1.1      | 2023-01-09 | 新增工具类tools     |
| 0.1.0      | 2023-01-07 | 发布第一个版本     |

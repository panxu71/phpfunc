phpfunc
====

[![License](https://img.shields.io/packagist/l/panxu/phpfunc.svg?style=flat-square)](LICENSE)
[![Php Version](https://img.shields.io/badge/php-%3E=7.3-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/panxu/phpfunc)
[![Packagist Version](https://img.shields.io/packagist/v/panxu/phpfunc)](https://packagist.org/packages/panxu/phpfunc)
[![star](https://gitee.com/panxu11/phpfunc/badge/star.svg?theme=gray)](https://gitee.com/panxu11/phpfunc/stargazers)
[![fork](https://gitee.com/panxu11/phpfunc/badge/fork.svg?theme=dark)](https://gitee.com/panxu11/phpfunc/members)
[![blog](https://img.shields.io/badge/blog-https%3A%2F%2Fpanxu.net-brightgreen)](https://panxu.net)

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
| Arr      | 数组 | 提供常用数组快捷方法     |

### 字符串函数说明

```php
use func\Str;
```

#### 字符串截取(msubstr)

```php
// 支持中文和其他编码
msubstr($str, $start = 0, $length = 15, $charset = "utf-8", $suffix = true)
Str::msubstr("phpfunc是一个简洁小巧且功能完善的PHP常用工具集",0,7); // phpfunc
```

#### 返回指定范围随机字符串(randomString)

```php
// 
randomString($length = 6, $isupper = true, $islower = false, $isspec = false)
Str::randomString(10); // iffpndyh2t
```

#### 返回全局唯一UUID(uuid)

```php
uuid(bool $isConnector = true, bool $isMark = true, bool $islower = false)
Str::uuid();// {b86cc5ed-0736-4d7a-b5f6-e0aa52d3a5df}
```

#### 中文转拼音(pinyin)

```php
// utf8版,gbk转utf8也可用
pinyin($str, $ret_format = 'all', $placeholder = '_', $allow_chars = '/[a-zA-Z\d ]/')
Str::pinyin("我是中国人"); // wo shi zhong guo ren
```

#### 返回指定范围内的随机姓名(randomName)

```php
randomName($sex = 0, $issurname = null, $iscompound = true)
Str::randomName();
// Array
// (
//     [surname] => 聂
//     [name] => 辰
//     [username] => 聂辰
// )
```

#### 返回随机邮箱(randomEmail)

```php
// 支持邮箱类型[126,163,139,189,qq,wo,sina,sohu,aliyun,foxmail,outlook]
randomEmail($type = "", $len = 6)
Str::randomEmail("sohu"); // 4wxbno@sohu.com
```

#### 返回指定范围内的随机时间(randomDate)

```php
randomDate($begin = "", $end = "", $now = true)
Str::randomDate(); // 2023-01-10 11:57:23
```

#### 返回随机手机号码(randomPhone)

```php
// 默认随机;0,移动;1,联通;2,电信
randomPhone($operator = null)
Str::randomPhone(); // 18952373556
```

#### 返回随机身份证号(randomCardId)

```php
randomCardId()
Str::randomCardId(); // 340421200511098459（可验证）
```

#### 返回随机金额或小数(randomFloat)

```php
randomFloat($num = 2, $min = 0, $max = 10)
Str::randomFloat(); // 9.18
```

#### 返回随机省份或列表(randomProvince)

```php
// 中国共计34个省级行政区，包括23个省、5个自治区、4个直辖市、2个特别行政区
randomProvince(bool $isIndex = false, bool $isAll = false)
Str::randomProvince();
// Array
// (
//     [index] => 14
//     [province] => 山东省
// )
```

#### 字符串转数组(stringToArray)

```php
// 支持中文英文混合字符串
stringToArray(string $string, string $charset = "utf-8")
Str::stringToArray("张a三");
// Array
// (
//     [0] => 张
//     [1] => a
//     [2] => 三
// )
```

#### 数据脱敏(dataMasking)(更新中)

```php
// 支持指定类型及自定义规则脱敏
// 类型($type) [1姓名,2出生日期,3手机号,4身份证,5银行卡号,6电子邮箱]
dataMasking(string $string = "", int $type = 0, array $index = [], string $replace = "*")

Str::dataMasking(Str::randomName()['username'], 1);//司马**
Str::dataMasking("1990/10/11", 2);//19**/**/**
Str::dataMasking(self::randomPhone(), 3);//181****9191
Str::dataMasking(self::randomCardId(), 4);//51152720******2457
Str::dataMasking("6225365271562822", 5);//622536********22
Str::dataMasking(Str::randomEmail(), 6); //******@qq.com
// 自定义规则
Str::dataMasking("内蒙古锡林郭勒盟二连浩特市", 0, [4, 5, 6, 7, 8]); //内蒙古锡*****连浩特市
```

#### 金额转换为中文大写金额(amountConvert)

```php
amountConvert(float $amount = 0.00, bool $isround = true, int $type = 0)
Str::amountConvert(10000000.34, false);//壹仟万元叁角肆分
```

#### 返回随机颜色(randomColor)

```php
// columns 0英文名|1中文名|2十六进制|3RGB
randomColor(int $num = 2, array $columns = [2])
Str::randomColor();
// Array
// (
//     [0] => Array
//         (
//             [0] => #7B68EE
//         )
//     [1] => Array
//         (
//             [0] => #808080
//         )
// )
```

### 数组函数说明

```php
use func\Str;
```

#### 比较数组是否相等(equal)

```php
// 比较数组是否相等
equal(array $arr1, array $arr2)
// 使用案例：
Arr::equal([['id' => 1, 'name' => "广东省", 'pid' => 0]],[['id' => 1, 'name' => "广东省", 'pid' => 1]])
// false
```

#### 返回所有下级节点(tree)

```php
tree($list = [], $keyName = "pid")
Arr::tree([
    ['id' => 1, 'name' => "广东省", 'pid' => 0],
    ['id' => 2, 'name' => "深圳市", 'pid' => 1],
    ['id' => 3, 'name' => "龙华区", 'pid' => 2],
    ['id' => 4, 'name' => "民治街道", 'pid' => 3],
]);
// Array
// (
//     [0] => Array
//         (
//             [id] => 1
//             [name] => 广东省
//             [pid] => 0
//             [children] => Array
//                 (
//                     [0] => Array
//                         (
//                             [id] => 2
//                             [name] => 深圳市
//                             [pid] => 1
//                             [children] => Array
//                                 (
//                                     [0] => Array
//                                         (
//                                             [id] => 3
//                                             [name] => 龙华区
//                                             [pid] => 2
//                                             [children] => Array
//                                                 (
//                                                     [0] => Array
//                                                         (
//                                                             [id] => 4
//                                                             [name] => 民治街道
//                                                             [pid] => 3
//                                                         )
//                                                 )
//                                         )
//                                 )
//                         )
//                 )
//         )
// )
```

#### 返回所有节点等级(classify)

```php
classify(array $data, int $id = 0, int $level = 0, string $keyName = "pid")
Arr::classify([
    ['id' => 1, 'name' => "广东省", 'pid' => 0],
    ['id' => 2, 'name' => "深圳市", 'pid' => 1],
    ['id' => 3, 'name' => "龙华区", 'pid' => 2],
    ['id' => 4, 'name' => "民治街道", 'pid' => 3],
]);
// Array
// (
//     [0] => Array
//         (
//             [id] => 1
//             [name] => 广东省
//             [pid] => 0
//             [level] => 1
//         )
//     [1] => Array
//         (
//             [id] => 2
//             [name] => 深圳市
//             [pid] => 1
//             [level] => 2
//         )
//     [2] => Array
//         (
//             [id] => 3
//             [name] => 龙华区
//             [pid] => 2
//             [level] => 3
//         )

// )
```

#### 获取指定节点的所有父节点(familyTree)

```php
familyTree(array $data, int $nodeId, string $keyName = "pid")
Arr::familyTree([
    ['id' => 1, 'name' => "广东省", 'pid' => 0],
    ['id' => 2, 'name' => "深圳市", 'pid' => 1],
    ['id' => 3, 'name' => "龙华区", 'pid' => 2],
    ['id' => 4, 'name' => "民治街道", 'pid' => 3],
]);
// Array
// (
//     [0] => Array
//         (
//             [id] => 1
//             [name] => 广东省
//             [pid] => 0
//         )
//     [1] => Array
//         (
//             [id] => 2
//             [name] => 深圳市
//             [pid] => 1
//         )
//     [2] => Array
//         (
//             [id] => 3
//             [name] => 龙华区
//             [pid] => 2
//         )
//     [3] => Array
//         (
//             [id] => 4
//             [name] => 民治街道
//             [pid] => 3
//         )
// )
```

#### 获取最次级节点(subordinate)

```php
subordinate(array $data = [], string $keyName = "pid")
Arr::subordinate([
    ['id' => 1, 'name' => "广东省", 'pid' => 0],
    ['id' => 2, 'name' => "深圳市", 'pid' => 1],
    ['id' => 3, 'name' => "龙华区", 'pid' => 2],
    ['id' => 7, 'name' => "湖南省", 'pid' => 0],
    ['id' => 8, 'name' => "长沙市", 'pid' => 7],
    ['id' => 9, 'name' => "望城区", 'pid' => 8],
]);
// Array
// (
//     [0] => Array
//         (
//             [id] => 3
//             [name] => 龙华区
//             [pid] => 2
//         )
//     [1] => Array
//         (
//             [id] => 9
//             [name] => 望城区
//             [pid] => 8
//         )
// )
```

#### 多维数组转一维(changeToSingle)

```php
changeToSingle(array $data = [], string $keyName = "pid")
Arr::changeToSingle([[1, 2, 3], [4, 5, 6], [7, 8, 9 => ['a', 'b' => ['c', 'd']]]]);
// Array
// (
//     [0] => 1
//     [1] => 2
//     [2] => 3
//     [3] => 4
//     [4] => 5
//     [5] => 6
//     [6] => 7
//     [7] => 8
//     [8] => a
//     [9] => c
//     [10] => d
// )
```

#### 返回多个数组的笛卡尔积(cartesianProduct)

```php
cartesianProduct(array $array = [])
Arr::cartesianProduct([['透气', '防滑'], ['37码', '38码', '39码'], ['男款', '女款']]);
// Array
// (
//     [0] => 透气,37码,男款
//     [1] => 透气,37码,女款
//     [2] => 透气,38码,男款
//     [3] => 透气,38码,女款
//     [4] => 透气,39码,男款
//     [5] => 透气,39码,女款
//     [6] => 防滑,37码,男款
//     [7] => 防滑,37码,女款
//     [8] => 防滑,38码,男款
//     [9] => 防滑,38码,女款
//     [10] => 防滑,39码,男款
//     [11] => 防滑,39码,女款
// )
```

#### 二维数组根据某个字段排序(sortByKey)

```php
sortByKey(array $array, string $keys, string $sort = SORT_DESC)
Arr::sortByKey([
    ['id' => 3, 'name' => "龙华区", 'pid' => 2],
    ['id' => 1, 'name' => "广东省", 'pid' => 0],
    ['id' => 2, 'name' => "深圳市", 'pid' => 1],
], 'pid'));
// Array
// (
//     [0] => Array
//         (
//             [id] => 3
//             [name] => 龙华区
//             [pid] => 2
//         )
//     [1] => Array
//         (
//             [id] => 2
//             [name] => 深圳市
//             [pid] => 1
//         )
//     [2] => Array
//         (
//             [id] => 1
//             [name] => 广东省
//             [pid] => 0
//         )

// )
```

### 常用工具类

|类名         |处理类型  |所属工具类 |  功能说明  |
| :-----:   |  :-----:  | :----:  | ----  |
| Date      |日期处理  |  tools |   常用日期处理方法     |
| Random      |随机处理  |  tools |   产生随机数据     |

### 日期类工具（Date）

```php
use func\tools\Date;
```

#### 返回指定年份开始和结束日期(year)

```php
year(int $year = 0, bool $isTimestamp = false)
Date::year();
// Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-12-31 23:59:59
// )
```

#### 返回指定季度开始和结束时间(quarter)

```php
quarter(int $season = 0, int $year = 0, bool $isTimestamp = false)
Date::quarter();
// Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-03-31 23:59:59
// )
```

#### 返回指定年份指定月份开始和结束日期(month)

```php
month(int $month = 0, int $year = 0, bool $isTimestamp = false)
Date::month();
// Array
// (
//     [start] => 2023-01-01 00:00:00
//     [end] => 2023-01-31 23:59:59
// )
```

#### 返回指定年份指定周开始和结束时间(week)

```php
week(int $week = 0, int $year = 0, bool $isTimestamp = false)
Date::week();
// Array
// (
//     [start] => 2023-01-09 00:00:00
//     [end] => 2023-01-15 23:59:59
// )
```

#### 时间格式化(format)

```php
// $date支持任意格式日期（必须是时间日期格式）
// 1、长度格式不限，年、月、日、时、分、秒（可选），但必须是按照年月日时分秒顺序，如：2022、20221213、2022121212……;
// 2、分隔符不限，"","-","/",（可选），如：202212，2022-12，2022/12
format(string|int $date): string
Date::format(2005) //2005-02-01 14:07:12
Date::format("200512") //2005-12-01 14:07:12
Date::format("20051220") //2005-12-20 14:07:12
Date::format("2005122011") //2005-12-20 11:07:12
```

### 随机类工具（Random）

```php
use func\tools\Random;
```

#### 随机姓名(name)

```php
// 支持英文名
name(string $surname = "", int $sex = 0, int $sanme = 0,  string $type = "zh")
Random::name();
// Array
// (
//     [surname] => 葛
//     [name] => 俊宛
//     [full_name] => 葛俊宛
// )
Random::name("王");
// Array
// (
//     [surname] => 王
//     [name] => 化瑞
//     [full_name] => 王化瑞
// )
```

#### 随机字符串(string)

```php
string(int $len = 6, bool $number = true, bool $lower = true, bool $upper = false, bool $special = false)
Random::string(); // hgukx0r
```

#### 随机邮箱(email)

```php
// 支持自定义域名及国外邮箱
email(string $domain = "", int $len = 8,  string $type = "zh")
Random::email(); 
// Array
// (
//     [domain] => yeah.net
//     [email] => kvpjugknk@yeah.net
// )
```

#### 随机日期(dates)

```php
// 支持指定年份和范围，默认随机近80年
dates(array $scope = [])
Random::dates(); 
// Array
// (
//     [timestamp] => 1093197790
//     [date] => 2004-08-23 02:03:10
// )
```

#### 随机身份证号(identityCard)

```php
// 支持指定地区、性别、生日，默认随机近80年
identityCard(int $regionCode = 0, int $sex = 0, int $birth = 0)
Random::identityCard(); // 370602202311081317
Random::identityCard(130107); // 130107202311081317
```

#### 随机金额(amount)

```php
amountamount($max = 100, $min = 0, $num = 2)
Random::amount(); // 36.54
```

#### 随机图片(images)

```php
images(int $width = 250, int $heigh = 160, int $limit = 1)
Random::images(); 
// Array
// (
//     [0] => https://picsum.photos/249/160?random=f1540363-43cb-f649-47e7-0d4ffb636d7c
// )
```

#### 随机头像(avatar)

```php
avatar()
Random::avatar(); // https://api.multiavatar.com/e3428cafedb61c5e116fe526ad6bfb3f.png
```

#### 随机银行卡号(bankCard)

```php
// 注意：仅供学习参考，请不要用于非法用途，否则后果自负
bankCard(int $type = 0, string $bank = "")
Random::bankCard(); 
// Array
// (
//     [bank_name] => 交通银行
//     [card_no] => 6282167982311568
// )
```

#### 随机地址(address)

```php
address():array
Random::address(); 
// Array
// (
//     [province] => 湖南省
//     [city] => 娄底市
//     [district] => 娄星区
//     [town] => 杉山镇
//     [code] => 431302100000
//     [address] => 湖南省娄底市娄星区杉山镇民壮村007号
// )
```

#### 随机公司名称(company)

```php
company():array
Random::company(); 
// Array
// (
//     [city] => 泸州市
//     [name] => 顿啸
//     [type] => 物业管理有限公司
//     [full_name] => 泸州市顿啸物业管理有限公司
// )
```

#### 随机手机号码(phone)

```php
phone(int $type = 0, int $operator = 0):array
Random::phone(); 
// Array
// (
//     [operator] => 187
//     [number] => 69551472
// )
```

### 版本更新

|版本 |日期 |说明  |
|:----:   | :----: | ----  |
| 0.1.5      | 2023-02-01 | 工具集新增随机类     |
| 0.1.4      | 2023-01-16 | 新增数组函数集Arry     |
| 0.1.3      | 2023-01-14 | no message     |
| 0.1.2      | 2023-01-12 | no message     |
| 0.1.1      | 2023-01-09 | 新增工具类tools     |
| 0.1.0      | 2023-01-07 | 发布第一个版本     |

### 最新记录

[![panxu/phpfunc](https://gitee.com/panxu11/phpfunc/widgets/widget_card.svg?colors=ffffff,1e252b,323d47,455059,d7deea,99a0ae)](https://gitee.com/panxu11/phpfunc)

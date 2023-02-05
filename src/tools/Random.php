<?php
// +----------------------------------------------------------------------
// | panxu/php-func(随机数据工具类)  
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.  
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 ) 
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>  
// +----------------------------------------------------------------------
namespace func\tools;

use func\Str;
use func\tools\Date;

class Random
{
    /**
     * loadConf
     *
     * @param string $fileName
     * @return string
     */
    public function loadConf(string $fileName): string
    {
        return file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . "datas" . DIRECTORY_SEPARATOR . $fileName . ".json");
    }

    /**
     * 随机姓名（支持英文名）
     *
     * @param string $surname 姓氏,默认随机,可指定
     * @param integer $sex    性别,默认随机,可指定(0,随机,1,男;2,女)
     * @param integer $sanme  中间名,默认随机,可选择(0,随机,1,包含;2,不包含)
     * @param string $type    类型,默认zh(zh,中国;en,国外)
     * @return array
     */
    public static function name(string $surname = "", int $sex = 0, int $sanme = 0,  string $type = "zh"): array
    {
        // 英文姓氏来源：https://namecensus.com/first-names/
        $nameDatas = json_decode((new self)->loadConf(__FUNCTION__), true);
        if (!isset($nameDatas[$type])) {
            return "姓名类型不存在";
        }
        if (!in_array($sex, [0, 1, 2])) {
            return "性别不存在";
        }
        $typeData        = $nameDatas[$type];
        // 姓氏
        $surname         = $surname != "" ? $surname : $typeData['surname'][array_rand($typeData['surname'], 1)];
        $sanme           = $type == "en" ? 1 : $sanme; //英文通常都会带上中间字
        // 中间字
        $sanme           = (!(!$sanme ? array_rand([0, 1], 1) : $sanme) || $sanme == 1) ? $typeData['sname'][array_rand($typeData['sname'], 1)] : "";
        $sex             = !$sex ? ['mname', 'fname'][array_rand(['mname', 'fname'], 1)] : ['mname', 'fname'][$sex - 1];
        // 姓名
        $lname           = $typeData[$sex][array_rand($typeData[$sex], 1)];

        $data['surname']   = $surname;
        $data['name']      = $sanme . $lname;
        $data['full_name'] = $type == "zh" ? $surname . $sanme . $lname : $lname . " " . $sanme . " " . $surname;
        return $data;
    }

    /**
     * 随机字符串
     *
     * @param integer $len     字符串长度,可指定
     * @param boolean $number  包含数字,默认是
     * @param boolean $lower   包含小写字母,默认是
     * @param boolean $upper   包含大写字母,,默认是
     * @param boolean $special 包含特殊字符,默认否
     * @return string
     */
    public static function string(int $len = 6, bool $number = true, bool $lower = true, bool $upper = false, bool $special = false): string
    {
        if ($len < 1) {
            return "字符串长度不合法";
        }
        $stringlDatas              = json_decode((new self)->loadConf(__FUNCTION__), true);
        $stringArray               = [];
        $number && $stringArray    = array_merge($stringArray, $stringlDatas['number']);
        $lower && $stringArray     = array_merge($stringArray, $stringlDatas['lower']);
        $upper && $stringArray     = array_merge($stringArray, $stringlDatas['upper']);
        $special && $stringArray   = array_merge($stringArray, $stringlDatas['special']);
        shuffle($stringArray); //打乱数组顺序
        $string = "";
        for ($i = 0; $i <= $len; $i++) {
            $string .= $stringArray[array_rand($stringArray, 1)];
            !$i && $string == "0" && $string += 1; //保证不以0开头
        }
        return $string;
    }

    /**
     * 随机邮箱
     *
     * @param string $domain 域名,默认随机,可指定
     * @param integer $len   邮箱名,默认长度8,可指定
     * @param string $type   邮箱类型,默认zh,可指定(zh,中国;en,国外)
     * @return array
     */
    public static function email(string $domain = "", int $len = 8,  string $type = "zh"): array
    {
        // 域名后缀参考来源：https://help.aliyun.com/document_detail/35751.html#section-htk-ycd-b2b
        if ($domain != "" && !preg_match("/^[A-Za-z0-9]+\.(cn|com|net|net.cn)$/", $domain)) {
            return "邮箱类型不合法";
        }
        $emailDatas     = json_decode((new self)->loadConf(__FUNCTION__), true);
        $emailList      = array_column(array_values($emailDatas[$type]), 'domain');
        $data['domain'] = $emailList[array_rand($emailList, 1)];
        $data['email']  = self::string($len, false) . "@" . $data['domain'];
        return $data;
    }

    /**
     * 随机日期
     *
     * @param array $scope 指定范围，不指定则默认近80年随机
     * @return void
     */
    public static function dates(array $scope = [])
    {
        $scope = array_filter($scope);
        switch (count($scope)) {
            case 0:
                $scope = Date::year(date("Y", strtotime("-" . rand(0, 80) . " year")));
                break;
            case 1:
                $scope = Date::year(date("Y", strtotime(Date::format($scope[0]))));
                pp($scope);
                break;
            case 2:
                $scope['start'] = Date::format($scope[0]);
                $scope['end']   = Date::format($scope[1]);
                $scope['start'] > $scope['end'] && list($scope['start'], $scope['end']) = [$scope['end'], $scope['start']];
                break;
        }

        $data['timestamp'] = rand(strtotime($scope['start']), strtotime($scope['end']));
        $data['date'] = date("Y-m-d H:i:s", $data['timestamp']);
        return $data;
    }

    /**
     * 随机身份证号
     *
     * @param integer $regionCode 指定省市区,默认随机
     * @param integer $sex        性别,默认随机(0,随机;1,男;2,女)
     * @param integer $birth      生日,默认随机
     * @return void
     */
    public static function identityCard(int $regionCode = 0, int $sex = 0, int $birth = 0)
    {
        // 行政区划数据来源：http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/
        if ($regionCode != 0 && !preg_match("/^[1-6]{1}[0-9]{5}$/", $regionCode)) {
            return "行政区划不合法";
        } else {
            $regionDatas = json_decode((new self)->loadConf("region"), true);
            $codeList    = [];
            foreach ($regionDatas as $v) {
                $tmpRegionCode = substr($v['code'], 0, 6);
                $codeList[$tmpRegionCode] = $tmpRegionCode;
            }
        }
        // 区域代码
        $regionCode = $regionCode ? $regionCode : (count($codeList) ? $codeList[array_rand($codeList, 1)] : "100000");
        // 出生日期
        $birth = date("Ymd", self::dates([$birth])['timestamp']);
        // 生成前17位
        $base = $regionCode . $birth . mt_rand(0, 9) . mt_rand(0, 9) . [mt_rand(0, 9), [1, 3, 5, 7, 9][mt_rand(0, 4)], [0, 2, 4, 6, 8][mt_rand(0, 4)]][$sex];
        // 计算校验位
        $sums = 0;
        for ($i = 0; $i < 17; $i++) {
            $sums += substr($base, $i, 1) * [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2][$i];
        }
        return  $base . ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'][$sums % 11];
    }

    /**
     * 随机金额
     *
     * @param integer $max 最大值
     * @param integer $min 最小值
     * @param integer $num 小数位,默认两位
     * @return void
     */
    public static function amount($max = 100, $min = 0, $num = 2)
    {
        return sprintf("%." . $num . "f", $min + mt_rand() / mt_getrandmax() * ($max - $min));
    }

    /**
     * 随机图片
     *
     * @param integer $width 图片宽度,默认250
     * @param integer $heigh 图片高度,默认160
     * @param integer $limit 图片数量,默认1
     * @return void
     */
    public static function images(int $width = 250, int $heigh = 160, int $limit = 1): array
    {
        $images = [];
        $limit = $limit < 1 ? 1 : $limit;
        for ($i = 0; $i < $limit; $i++) {
            $images[$i] = "https://picsum.photos/{$width}/{$heigh}?random=" . Str::uuid(true, false);
        }
        return $images;
    }

    /**
     * 随机头像
     *
     * @return void
     */
    public static function avatar(): string
    {
        // https://www.bossdesign.cn/multiavatar/
        $uuid = Str::uuid(false, false);
        return "https://api.multiavatar.com/{$uuid}.png";
    }

    /**
     * 随机银行卡号
     * 仅供学习参考，请不要用于非法用途，否则后果自负
     * @param integer $type 卡类型,默认0(0,随机;1,银行卡;2,信用卡)
     * @param string  $bank  银行名称,默认随机
     * @return void
     */
    public static function bankCard(int $type = 0, string $bank = ""): array
    {
        $bankDatas = json_decode((new self)->loadConf("bank"), true);
        $binList   = $bankDatas['bin'];
        $bankList  = array_unique(array_column($binList, "bank_name"));
        // 卡片类型
        $type = ($type != 1 && $type != 2) ?  mt_rand(0, 1) : $type;
        $bankName = $bank != "" && in_array($bank, $bankList) ? $bank : "";
        if ($bankName != "") {
            foreach ($binList as $v) {
                if ($v["bank_name"] == $bankName && $type - 1 == $v['type']) {
                    $bankInfo = $v;
                }
            }
        } else {
            do {
                $bankInfo = $binList[array_rand($binList, 1)];
            } while ($bankInfo['card_len'] >= 16 && $type - 1 != (int)$bankInfo['type'] && !preg_match("/[\x80-\xff]{4,8}/", $bankInfo['bank_name']));
        }

        $cardNumber = $bankInfo["bin"];
        # generate digits
        while (strlen($cardNumber) < ($bankInfo["card_len"] - 1)) {
            $cardNumber .= rand(0, 9);
        }
        # Calculate sum
        $sum = 0;
        $pos = 0;
        $reversedCardNumber = strrev($cardNumber);
        while ($pos < $bankInfo["card_len"] - 1) {
            $odd = $reversedCardNumber[$pos] * 2;
            if ($odd > 9) {
                $odd -= 9;
            }
            $sum += $odd;
            if ($pos != ($bankInfo["card_len"] - 2)) {
                $sum += $reversedCardNumber[$pos + 1];
            }
            $pos += 2;
        }
        # Calculate check digit
        $cardNumber .= ((floor($sum / 10) + 1) * 10 - $sum) % 10;
        $data['bank_name'] = $bankInfo['bank_name'];
        $data['card_no']   = $cardNumber;
        return $data;
    }

    /**
     * 随机地址
     *
     * @return array
     */
    public static function address(): array
    {
        // 小区名称参考：https://www.anjuke.com/changsha/cm/
        $regionDatas  = json_decode((new self)->loadConf("region"), true);
        do {
            $region   = $regionDatas[array_rand($regionDatas, 1)];
        } while ($region['level'] != 3);

        $randomInfo      = Arr::familyTree($regionDatas, $region["id"]);
        $address         = array_column($randomInfo, "name");
        $addressData     = json_decode((new self)->loadConf(__FUNCTION__), true);
        $number          = str_pad(mt_rand(1, 100), 3, "0", STR_PAD_LEFT);
        $fool            = mt_rand(1, 30);
        $village         = mt_rand(1, 200) . "栋";
        $room            = $fool  . $number . "室";

        $address[4] = $addressData["street"][array_rand($addressData["street"], 1)] . "村" . $number . "号";

        if (strpos($address[3], "街道") !== false || strpos($address[3], "区") !== false) {
            $type  = ["street", "mansion", "neighbourhood"][mt_rand(0, 2)];
            $type  == "street" &&
                $address[4] = $addressData["street"][array_rand($addressData["street"], 1)] . ["路", "村"][mt_rand(0, 1)] . $village . $room;
            $type  == "mansion" &&
                $address[4] = $addressData["mansion"][array_rand($addressData["mansion"], 1)] . "大厦" . ["A", "B", "C", "D", "E"][array_rand(["A", "B", "C", "D", "E"])] . "座" . $fool . "层" . $room;
            $type  == "neighbourhood" &&
                $address[4] = $addressData["neighbourhood"][array_rand($addressData["neighbourhood"], 1)] . $village . $room;
        }

        $address[5] = $region['code'];
        return $address;
    }

    /**
     * 随机公司名称
     *
     * @return void
     */
    public static function company()
    {
        $companyDatas = json_decode((new self)->loadConf(__FUNCTION__), true);
        $regionDatas  = json_decode((new self)->loadConf("region"), true);
        do {
            $region   = $regionDatas[array_rand($regionDatas, 1)];
        } while ($region['level'] != 1);
        $data["city"] = $region["name"];
        $data["name"] = $companyDatas["name"][array_rand($companyDatas["name"], 1)];
        $data["type"] = $companyDatas["type"][array_rand($companyDatas["type"], 1)] . ["有限", "有限责任", "股份有限", "集团有限"][mt_rand(0, 3)] . "公司";
        return $data;
    }
}

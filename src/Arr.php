<?php
// +----------------------------------------------------------------------
// | panxu/php-func(数组常用方法)
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2023 https://panxu.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: panxu <panxu71@163.com>
// +----------------------------------------------------------------------
namespace func;

class Arr
{
    /**
     * 无限极分类(引用的方式)
     * @param  array $list 分类数据
     * @param  array $keyName 父id字段名
     * @return array 
     */
    public static function tree(array $list = [], string $keyName = "pid"): array
    {
        $data = array_column($list, null, 'id');
        foreach ($data as $k => $v) {
            if ($v[$keyName] > 0) {
                $data[$v[$keyName]]['children'][] = &$data[$k]; //不是根节点的将自己的地址放到父级的child节点
            } else {
                $tree[] = &$data[$v['id']]; //根节点直接把地址放到新数组中
            }
        }
        return $tree ?? [];
    }
}

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
     * 比较数组是否相等
     *
     * @param array $arr1
     * @param array $arr2
     * @return boolean
     */
    public static function equal(array $arr1, array $arr2): bool
    {
        if (count($arr1) != count($arr2)) {
            return false;
        }
        //strcmp区分大小写 strcasecmp二进制安全比较字符串（不区分大小写）
        if (strcmp(serialize($arr1), serialize($arr2)) == 0) {
            return true;
        }
        return false;
    }

    /**
     * 无限极分类(引用的方式)
     * @param  array $list 分类数据
     * @param  string $keyName 父id字段名
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
    /**
     * 无限极分类(递归方式)
     * @param  array  $data   数据源
     * @param  integer $pid   父ID
     * @param  integer $level 分级标识
     * @param  array $keyName 父id字段名
     * @return array
     */
    public static function classify(array $data, int $id = 0, int $level = 0, string $keyName = "pid"): array
    {
        $list = []; //子孙数组
        foreach ($data as $v) {
            if ($v[$keyName] == $id) {
                $v['level'] = $level;
                $list[] = $v;
                $list = array_merge($list, self::classify($data, $v['id'], $level + 1));
            }
        }
        return $list ?? [];
    }

    /**
     * 获取家族树节点
     * @param  array   $list 所有节点
     * @param  integer $cid  指定节点
     * @return array         返回指定节点的所有父节点
     */

    /**
     * 获取指定节点的所有父节点
     *
     * @param array $data     数据源
     * @param integer $nodeId 节点ID
     * @param string $keyName 父id字段名
     * @return array
     */
    public static function familyTree(array $data, int $nodeId, string $keyName = "pid"): array
    {
        if (!$nodeId) return [];
        while ($nodeId > 0) {
            foreach ($data as $v) {
                if ($v['id'] == $nodeId) {
                    $tree[]  = $v;
                    $nodeId  = $v[$keyName];
                }
            }
        }
        return array_reverse($tree) ?? [];
    }

    /**
     * 获取最次一级节点
     *
     * @param array $data  数据源
     * @param string $keyName 节点字段名
     * @return array
     */
    public static function subordinate(array $data = [], string $keyName = "pid"): array
    {
        $ids = array_column($data, $keyName);
        foreach ($data as $v) {
            if (!in_array($v['id'], $ids)) {
                $tree[] = $v;
            }
        }
        return $tree ?? [];
    }

    /**
     * 多维数组转一维
     *
     * @param array $data
     * @return array
     */
    public static function changeToSingle(array $data): array
    {
        $return = [];
        array_walk_recursive($array, function ($x, $index) use (&$return) {
            $return[] = $x;
        });
        return $return;
    }
}

<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace catcher;

class Tree
{
    protected static string $pk = 'id';

    /**
     *
     * @param array $items
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     *@author CatchAdmin
     * @time 2021年05月25日
     */
    public static function done(array $items, int $pid = 0, string $pidField = 'parent_id', string $children = 'children'): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item[$pidField] == $pid) {
                $child = self::done($items, $item[self::$pk], $pidField);
                if (count($child)) {
                    $item[$children] = $child;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * set pk field
     *
     * @author CatchAdmin
     * @time 2021年05月25日
     * @param string $pk
     * @return $this
     */
    public static function setPk(string $pk): self
    {
        self::$pk = $pk;

        return new self();
    }
}

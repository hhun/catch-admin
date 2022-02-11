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

namespace catch;

use catch\library\excel\Excel;
use catch\library\excel\ExcelContract;
use PhpOffice\PhpSpreadsheet\Exception;
use Psr\SimpleCache\InvalidArgumentException;
use think\facade\Cache;
use think\model\Collection;

class CatchModelCollection extends Collection
{
    /**
     * tree 结构
     *
     * @time 2020年10月21日
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     */
    public function toTree(int $pid = 0, string $pidField = 'parent_id', string $children = 'children'): array
    {
        $pk = 'id';

        if ($this->count()) {
            $pk = $this->first()->getPk();
        }

        return Tree::setPk($pk)->done($this->toArray(), $pid, $pidField, $children);
    }


    /**
     * 导出数据
     *
     * @time 2020年10月21日
     * @param $header
     * @param string $path
     * @param string $disk
     * @return array
     * @throws Exception
     */
    public function export($header, string $path = '', string $disk = 'local'): array
    {
        $excel = new class ($header, $this->items) implements ExcelContract {
            protected array $headers;

            protected array $sheets;

            public function __construct($headers, $sheets)
            {
                $this->headers = $headers;

                $this->sheets = $sheets;
            }

            public function headers(): array
            {
                // TODO: Implement headers() method.
                return $this->headers;
            }

            public function sheets(): array
            {
                // TODO: Implement sheets() method.
                return $this->sheets;
            }
        };

        if (! $path) {
            $path = Utils::publicPath('exports');
        }

        return (new Excel())->save($excel, $path, $disk);
    }

    /**
     * 缓存 collection
     *
     * @time 2020年10月21日
     * @param string $key
     * @param int $ttl
     * @param string $store
     * @return bool
     * @throws InvalidArgumentException
     */
    public function cache(string $key, int $ttl = 0, string $store = 'redis'): bool
    {
        return Cache::store($store)->set($key, $this->items, $ttl);
    }

    /**
     * 获取当前级别下的所有子级
     *
     * @time 2020年11月04日
     * @param array $ids
     * @param string $parentFields
     * @param string $column
     * @return array
     */
    public function getAllChildrenIds(array $ids, string $parentFields = 'parent_id', string $column = 'id'): array
    {
        array_walk($ids, function (&$item) {
            $item = intval($item);
        });

        $childIds = $this->whereIn($parentFields, $ids)->column($column);

        if (count($childIds)) {
            $childIds = array_merge($childIds, $this->getAllChildrenIds($childIds));
        }

        return $childIds;
    }

    /**
     * implode
     *
     * @time 2021年02月24日
     * @param string $separator
     * @param string $column
     * @return string
     */
    public function implode(string $column = '', string $separator = ','): string
    {
        return implode($separator, $column ? array_column($this->items, $column) : $this->items);
    }
}

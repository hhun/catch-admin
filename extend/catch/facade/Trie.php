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

namespace catch\facade;

use think\Facade;

/**
 * @method static \catch\library\Trie add(string $word)
 * @method static \catch\library\Trie filter(string $content)
 *
 * @time 2020年05月22日
 */
class Trie extends Facade
{
    protected static function getFacadeClass(): string
    {
        return \catch\library\Trie::class;
    }
}

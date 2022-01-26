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
 * @method static \catch\support\client\Http headers(array $headers)
 * @method static \catch\support\client\Http body($body)
 * @method static \catch\support\client\Http json(array $data)
 * @method static \catch\support\client\Http query(array $query)
 * @method static \catch\support\client\Http form(array $params)
 * @method static \catch\support\client\Http timeout($timeout)
 * @method static \catch\support\client\Http get(string $url)
 * @method static \catch\support\client\Http post(string $url)
 * @method static \catch\support\client\Http put(string $url)
 * @method static \catch\support\client\Http delete(string $url)
 * @method static \catch\support\client\Http token(string $token)
 * @method static \catch\support\client\Http ignoreSsl()
 * @method static \catch\support\client\Http attach($name, $resource, $filename)
 *
 * @time 2020年05月22日
 */
class Http extends Facade
{
    protected static $alwaysNewInstance = true;

    protected static function getFacadeClass(): string
    {
        return \catch\support\client\Http::class;
    }
}

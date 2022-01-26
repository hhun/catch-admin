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

namespace catch\event;

use think\App;
use think\Route;

class LoadModuleRoutes
{
    /**
     * 处理
     *
     * @time 2019年11月29日
     * @return void
     */
    public function handle(): void
    {
        $router = app(Route::class);

        $domain = config('catch.domain');

        $paths = app(App::class)->make('routePath')->get();

        if ($domain) {
            $router->domain($domain, function () use ($router, $paths) {
                foreach ($paths as $path) {
                    include $path;
                }
            });
        } else {
            $router->group(function () use ($router, $paths) {
                foreach ($paths as $path) {
                    include $path;
                }
            });
        }
    }
}

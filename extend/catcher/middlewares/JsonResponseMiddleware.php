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

namespace catcher\middlewares;

use think\Middleware;
use think\Request;

class JsonResponseMiddleware extends Middleware
{
    public function handle(Request $request, \Closure $next)
    {
        $server = $request->server();
        $server['HTTP_ACCEPT'] = 'application/json';
        $request->withServer($server);

        return $next($request);
    }
}

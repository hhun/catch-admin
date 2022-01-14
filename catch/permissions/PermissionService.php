<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\permissions;

use catchAdmin\permissions\event\OperateLogEvent;
use catchAdmin\permissions\middleware\AuthTokenMiddleware;
use catchAdmin\permissions\middleware\PermissionsMiddleware;
use catchAdmin\permissions\middleware\RecordOperateMiddleware;
use catcher\ModuleService;

class PermissionService extends ModuleService
{
    public function register()
    {
        parent::register();

        $this->registerMiddleWares();
    }

    /**
     * @time 2022年01月14日
     * @return string[][]
     */
    public function loadEvents(): array
    {
        return [
            'HttpEnd' => [OperateLogEvent::class]
        ];
    }

    /**
     * @time 2022年01月14日
     * @return string
     */
    public function loadRouteFrom(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'route.php';
    }

    /**
     * @time 2022年01月14日
     */
    protected function registerMiddleWares()
    {
        $middleware = $this->app->config->get('middleware');

        $middleware['alias']['auth'] = [
            AuthTokenMiddleware::class,
            PermissionsMiddleware::class,
            // RecordOperateMiddleware::class
        ];

        $this->app->config->set($middleware, 'middleware');
    }
}

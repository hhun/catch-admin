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
        parent::register(); // TODO: Change the autogenerated stub

        $this->registerMiddleWares();
    }

    public function loadEvents()
    {
        return [
            'operateLog' => [OperateLogEvent::class],
        ];
    }

    public function loadRouteFrom()
    {
        // TODO: Implement loadRouteFrom() method.
        return __DIR__.DIRECTORY_SEPARATOR.'route.php';
    }


    protected function registerMiddleWares()
    {
        $middleware = $this->app->config->get('middleware');

        $middleware['alias']['auth'] = [
            AuthTokenMiddleware::class,
            PermissionsMiddleware::class,
            RecordOperateMiddleware::class
        ];

        $this->app->config->set($middleware, 'middleware');
    }
}

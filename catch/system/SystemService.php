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

namespace catchAdmin\system;

use catchAdmin\system\events\AttachmentEvent;
use catch\command\MigrateRunCommand;
use catch\command\SeedRunCommand;
use catch\ModuleService;

class SystemService extends ModuleService
{
    public function loadRouteFrom()
    {
        // TODO: Implement loadRouteFrom() method.
        return __DIR__.DIRECTORY_SEPARATOR.'route.php';
    }

    public function loadEvents()
    {
        return [
            'attachment' => [AttachmentEvent::class],
        ];
    }

    protected function registerCommands()
    {
        $this->commands([
            MigrateRunCommand::class,
            SeedRunCommand::class,
        ]);
    }
}

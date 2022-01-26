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

namespace catch\validates;

class Sometimes implements ValidateInterface
{
    public function type(): string
    {
        return 'sometimes';
    }

    public function verify($value): bool
    {
        return (bool) ($value);
    }

    public function message(): string
    {
        return '';
    }
}

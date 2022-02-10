<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catch\support\form\fields;

use catch\support\form\element\components\Cascader;

/**
 *
 * @time 2021年09月26日
 */
class Area extends Cascader
{
    public static function make(string $name, string $title, array $props = []): self
    {
        $area = new self($name, $title);

        $area->prop('checkStrictly', true);

        $area->prop('label', 'name');

        $area->prop('value', 'id');

        return $area->props($props)->clearable()->filterable();
    }
}

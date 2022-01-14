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

namespace Catcher\Support\form\fields;

use catcher\support\form\element\components\Input;

class Url extends Input
{
    /**
     *
     * @time 2021年08月11日
     * @param string $name
     * @param string $title
     * @return Url
     */
    public static function make(string $name, string $title): Url
    {
        $url = new self($name, $title);

        return $url->type(Input::TYPE_URL)->clearable();
    }
}

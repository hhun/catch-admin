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

use catch\support\form\element\components\Input;

class Textarea extends Input
{
    public static function make(string $name, string $title): Textarea
    {
        $textArea = new self($name, $title);

        return $textArea->type(Input::TYPE_TEXTAREA)->clearable();
    }
}

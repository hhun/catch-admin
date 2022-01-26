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

class Password extends Input
{
    public static function make(string $name, string $title = '密码'): Password
    {
        $password = new self($name, $title);

        return $password->type(Input::TYPE_PASSWORD)->clearable();
    }

    /**
     * password show
     *
     * @time 2021年08月24日
     * @return $this
     */
    public function show(): self
    {
        $this->showPassword();

        return $this;
    }
}

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

class Avatar extends ImageUpload
{
    /**
     * make
     *
     * @time 2021年08月09日
     * @param string $name
     * @param string $title
     * @param string $action
     * @param bool $auth
     * @return ImageUpload
     */
    public static function make(string $name, string $title, string $action, bool $auth = true): ImageUpload
    {
        $avatar =  parent::make($name, $title, $action, $auth);

        return $avatar->single();
    }
}

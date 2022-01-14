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

use catcher\support\form\element\components\Tree as TreeComponent;

class Tree extends TreeComponent
{
    /**
     *
     * @time 2021年08月11日
     * @param string $name
     * @param string $title
     * @return static
     */
    public static function make(string $name, string $title): self
    {
        // TODO: Implement make() method.
        return new self($name, $title);
    }
}

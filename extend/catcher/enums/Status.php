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

namespace catcher\enums;

/**
 * status 状态枚举
 *
 * 1: 开启
 *
 * 2: 禁用
 *
 */
enum Status : int
{
    case Enable = 1;

    case Disable = 2;

    /**
     * @desc 描述
     *
     * @time 2022年01月14日
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            Status::Enable => '启用',

            Status::Disable => '禁用'
        };
    }
}

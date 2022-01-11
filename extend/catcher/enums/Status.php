<?php
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

}
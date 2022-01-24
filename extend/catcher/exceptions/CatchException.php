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

namespace catcher\exceptions;

use Exception;
use think\exception\HttpException;
use catcher\enums\Code;

abstract class CatchException extends HttpException
{
    protected const HTTP_SUCCESS = 200;

    public function __construct(string $message = '', int|Code $code = 0, Exception $previous = null, array $headers = [], $statusCode = 0)
    {
        parent::__construct($statusCode, $message ?: $this->getMessage(), $previous, $headers, $code instanceof Code ? $code->value : $code);
    }

    public function getStatusCode(): int
    {
        return self::HTTP_SUCCESS;
    }
}

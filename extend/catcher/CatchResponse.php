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

namespace catcher;

use think\Paginator;
use think\response\Json;
use catcher\enums\Code;

class CatchResponse
{
    /**
     * 成功的响应
     *
     * @time 2019年12月02日
     * @param mixed $data
     * @param string $msg
     * @param Code $code
     * @return Json
     */
    public static function success(array $data, string $msg = '', Code $code = Code::SUCCESS): Json
    {
        return json([
            'code' => $code->value,
            'message' => $msg ? : $code->message(),
            'data' => $data,
        ]);
    }

    /**
     * 分页
     *
     * @time 2019年12月06日
     * @param Paginator $list
     * @return Json
     */
    public static function paginate(Paginator $list): Json
    {
        return json([
            'code' => Code::SUCCESS->value,
            'message' => 'success',
            'count' => $list->total(),
            'current' => $list->currentPage(),
            'limit' => $list->listRows(),
            'data' => $list->getCollection(),
        ]);
    }

    /**
     * 错误的响应
     *
     * @time 2019年12月02日
     * @param string $msg
     * @param Code $code
     * @return Json
     */
    public static function fail(string $msg = '', Code $code = Code::FAILED): Json
    {
        return json([
            'code' => $code->value,
            'message' => $msg ? : $code->message(),
        ]);
    }
}

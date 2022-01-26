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

namespace catch;

use catch\exceptions\CatchException;
use catch\exceptions\FailedException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\ErrorException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use catch\enums\Code;

class CatchExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        // HttpException::class,
        // HttpResponseException::class,
        // ModelNotFoundException::class,
        DataNotFoundException::class,
         ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     * @throws \Exception
     */
    public function render($request, Throwable $e): Response
    {
        // 其他错误交给系统处理
        if ($e instanceof \Exception && ! $e instanceof CatchException) {
            $e = new FailedException($e->getMessage(), 10005, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * 重写异常渲染页面
     *
     * @time 2020年05月22日
     * @param Throwable $exception
     * @return string
     */
    protected function renderExceptionContent(Throwable $exception): string
    {
        ob_start();
        $data = $this->convertExceptionToArray($exception->getPrevious() ?: $exception);
        extract($data);
        include $this->app->config->get('app.exception_tmpl') ?: __DIR__.'/../../tpl/think_exception.tpl';

        return ob_get_clean();
    }

    /**
     * @desc rewrite parent getCode
     *
     * @time 2022年01月14日
     * @param Throwable $exception
     * @return int
     */
    protected function getCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        if (!$code && $exception instanceof ErrorException) {
            $code = $exception->getSeverity();
        }

        // 如果 code 是枚举对象，则使用 code->value 获取值
        return $code instanceof Code ? $code->value : $code;
    }
}

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

namespace catcher\base;

use app\Request;
use catcher\exceptions\FailedException;
use catcher\exceptions\ValidateFailedException;
use catcher\Utils;
use Exception;

class CatchRequest extends Request
{
    /**
     * @var bool
     */
    protected bool $needCreatorId = true;

    /**
     *  批量验证
     *
     * @var bool
     */
    protected bool $batch = false;

    /**
     * Request constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->validate();
    }

    /**
     * 初始化验证
     *
     * @return bool
     *@throws Exception
     */
    protected function validate(): bool
    {
        if (method_exists($this, 'rules')) {
            try {
                $validate = app('validate');
                // 批量验证
                if ($this->batch) {
                    $validate->batch($this->batch);
                }

                // 验证
                $message = [];
                if (method_exists($this, 'message')) {
                    $message = $this->message();
                }
                if (! $validate->message(empty($message) ? [] : $message)->check(request()->param(), $this->rules())) {
                    throw new FailedException($validate->getError());
                }
            } catch (Exception $e) {
                throw new ValidateFailedException($e->getMessage());
            }
        }

        // 设置默认参数
        if ($this->needCreatorId) {
            $this->param['creator_id'] = $this->user()->id;
        }

        return true;
    }

    /**
     * rewrite post
     *
     * @param string $name
     * @param null $default
     * @param string $filter
     * @return array|mixed|null
     */
    public function post($name = '', $default = null, $filter = '')
    {
        if ($this->needCreatorId) {
            $this->post['creator_id'] = $this->user()->id;
        }

        return parent::post($name, $default, $filter);
    }

    /**
     * 过滤空字段
     *
     * @return $this
     */
    public function filterEmptyField(): self
    {
        if ($this->isGet()) {
            $this->get = Utils::filterEmptyValue($this->get);
        } elseif ($this->isPost()) {
            $this->post = Utils::filterEmptyValue($this->post);
        } else {
            $this->put = Utils::filterEmptyValue($this->put);
        }

        return $this;
    }
}

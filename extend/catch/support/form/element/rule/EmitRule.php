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

namespace catch\support\form\element\rule;


trait EmitRule
{
    /**
     * 组件模式下配置使用emit方式触发的事件名
     * @var array
     */
    protected array $emit = [];

    /**
     * 自定义组件emit事件的前缀
     * @var string
     */
    protected string $emitPrefix;

    /**
     * @param array $emits
     * @return $this
     */
    public function emit(array $emits)
    {
        $this->emit = array_merge($this->emit, array_map('strval', $emits));

        return $this;
    }

    /**
     * @param string $emit
     * @return $this
     */
    public function appendEmit(string $emit)
    {
        $this->emit[] = $emit;

        return $this;
    }

    /**
     *
     * @param string $prefix
     * @return mixed
     */
    public function emitPrefix(string $prefix)
    {
        $this->emitPrefix = $prefix;

        return $prefix;
    }

    /**
     *
     * @return array
     */
    public function getEmit(): array
    {
        return $this->emit;
    }

    /**
     *
     * @return string
     */
    public function getEmitPrefix()
    {
        return $this->emitPrefix;
    }

    /**
     *
     * @return array
     */
    public function parseEmitRule(): array
    {
        $rule = [];

        if (count($this->emit)) {
            $rule['emit'] = $this->emit;
        }

        if (!is_null($this->emitPrefix)) {
            $rule['emitPrefix'] = $this->emitPrefix;
        }

        return $rule;
    }
}

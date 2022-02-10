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

namespace catch\support\form\element\driver;

abstract class FormComponent extends CustomComponent
{
    /**
     * @var mixed
     */
    protected $defaultValue = '';

    protected bool $selectComponent = false;

    /**
     * FormComponentDriver constructor.
     *
     * @param string $field 字段名
     * @param string $title 字段昵称
     * @param mixed|null $value 字段值
     */
    public function __construct(string $field, string $title, mixed $value = null)
    {
        parent::__construct();

        $this->field($field)
             ->title($title)
            ->value(is_null($value) ? $this->defaultValue : $value);

        if (isset(static::$propsRule['placeholder'])) {
            $this->placeholder($this->getPlaceHolder());
        }

        $this->init();
    }

    /**
     *
     * @time 2021年08月04日
     * @return void
     */
    protected function init()
    {

    }

    /**
     *
     * @time 2021年08月04日
     * @return mixed
     */
    abstract public function createValidate();

    /**
     * @param string|null $message
     * @return $this
     */
    public function required(string $message = null): self
    {
        if (is_null($message)) {
            $message = $this->getPlaceHolder();
        }

        $this->appendValidate($this->createValidate()->message($message)->required());

        return $this;
    }

    /**
     * @return string
     */
    protected function getPlaceHolder(): string
    {
        return sprintf('%s%s', $this->selectComponent ? '请选择' : '请输入', $this->title);
    }

    /**
     * default
     *
     * @time 2021年08月09日
     * @param $value
     * @return $this
     */
    public function default($value): FormComponent
    {
        $this->value = $value;

        return $this;
    }

    /**
     * set width
     *
     * @time 2021年08月09日
     * @param int $width
     * @return self
     */
    public function width(int $width): self
    {
        $this->style(['width' => $width . '%']);

        return $this;
    }

    /**
     * 100% width
     *
     * @time 2021年08月09日
     * @return $this
     */
    public function fullWidth(): self
    {
        return $this->width(100);
    }

    /**
     * half width
     *
     * @time 2021年08月09日
     * @return $this
     */
    public function halfWidth(): self
    {
        return $this->width(50);
    }
}

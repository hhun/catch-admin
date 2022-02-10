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

namespace catch\support\form\element\components;


use catch\support\form\element\driver\FormComponent;
use catch\support\form\element\rule\ValidateFactory;

/**
 * 时间选择器组件
 * Class TimePicker
 *
 * @method $this readonly(bool $readonly = true) 完全只读, 默认值: false
 * @method $this disabled(bool $disabled = true) 禁用, 默认值: false
 * @method $this editable(bool $editable = true) 文本框可输入, 默认值: true
 * @method $this clearable(bool $clearable = true) 是否显示清除按钮, 默认值: true
 * @method $this size(string $size) 输入框尺寸, 可选值: medium / small / mini
 * @method $this placeholder(string $placeholder) 非范围选择时的占位内容
 * @method $this startPlaceholder(string $startPlaceholder) 范围选择时开始日期的占位内容
 * @method $this endPlaceholder(string $endPlaceholder) 范围选择时开始日期的占位内容
 * @method $this isRange(bool $isRange = true) 是否为时间范围选择，仅对<el-time-picker>有效, 默认值: false
 * @method $this arrowControl(bool $arrowControl = true) 是否使用箭头进行时间选择，仅对<el-time-picker>有效, 默认值: false
 * @method $this align(string $align) 对齐方式, 可选值: left / center / right, 默认值: left
 * @method $this popperClass(string $popperClass) TimePicker 下拉框的类名
 * @method $this pickerOptions(array $pickerOptions) 当前时间日期选择器特有的选项参考下表, 默认值: {}
 * @method $this rangeSeparator(string $rangeSeparator) 选择范围时的分隔符, 默认值: '-'
 * @method $this valueFormat(string $valueFormat) 可选，仅TimePicker时可用，绑定值的格式。不指定则绑定值为 Date 对象, 可选值: 见日期格式
 * @method $this prefixIcon(string $prefixIcon) 自定义头部图标的类名, 默认值: el-icon-time
 * @method $this clearIcon(string $clearIcon) 自定义清空图标的类名, 默认值: el-icon-circle-close
 *
 */
class TimePicker extends FormComponent
{
    protected bool $selectComponent = true;

    protected array $defaultProps = [
        'isRange' => false,
        'editable' => false,
    ];

    protected static array $propsRule = [
        'readonly' => 'bool',
        'disabled' => 'bool',
        'isRange' => 'bool',
        'editable' => 'bool',
        'clearable' => 'bool',
        'size' => 'string',
        'placeholder' => 'string',
        'startPlaceholder' => 'string',
        'endPlaceholder' => 'string',
        'arrowControl' => 'bool',
        'align' => 'string',
        'popperClass' => 'string',
        'pickerOptions' => 'array',
        'rangeSeparator' => 'string',
        'valueFormat' => 'string',
        'prefixIcon' => 'string',
        'clearIcon' => 'string',
    ];

    /**
     * 下拉列表的时间间隔，数组的三项分别对应小时、分钟、秒。
     * 例如设置为 [1, 15] 时，分钟会显示：00、15、30、45。
     *
     * @param     $h
     * @param int $i
     * @param int $s
     * @return $this
     */
    public function steps($h, int $i = 0, int $s = 0): self
    {
        $this->props['steps'] = [$h, $i, $s];

        return $this;
    }

    public function createValidate()
    {
        return $this->props['isRange'] ? ValidateFactory::validateArr() : ValidateFactory::validateStr();
    }

    /**
     * @required
     *
     * @time 2021年08月04日
     * @param string|null $message
     * @return $this|TimePicker
     */
    public function required(string $message = null): TimePicker
    {
        if (is_null($message)) {
            $message = $this->getPlaceHolder();
        }

        $validate = $this->createValidate();

        if ($this->props['isRange']) {
            $required = ['required' => true, 'message' => $message];
            $validate->fields([
                '0' => $required,
                '1' => $required
            ]);
            return $this;
        }

        $this->appendValidate($validate->message($message)->required());

        return $this;
    }

}

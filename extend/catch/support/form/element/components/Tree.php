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
use think\model\Collection;

/**
 * 树型组件
 * Class Tree
 *
 * @method $this type(string $type) 类型，可选值为 checked、selected
 * @method $this emptyText(string $emptyText) 内容为空的时候展示的文本
 * @method $this nodeKey(string $nodeKey) 每个树节点用来作为唯一标识的属性，整棵树应该是唯一的
 * @method $this props(array $props) 配置选项，具体看下表
 * @method $this renderAfterExpand(bool $renderAfterExpand) 是否在第一次展开某个树节点后才渲染其子节点, 默认值: true
 * @method $this highlightCurrent(bool $highlightCurrent = true) 是否高亮当前选中节点，默认值是 false。, 默认值: false
 * @method $this defaultExpandAll(bool $defaultExpandAll = true) 是否默认展开所有节点, 默认值: false
 * @method $this expandOnClickNode(bool $expandOnClickNode) 是否在点击节点的时候展开或者收缩节点， 默认值为 true，如果为 false，则只有点箭头图标的时候才会展开或者收缩节点。, 默认值: true
 * @method $this checkOnClickNode(bool $checkOnClickNode = true) 是否在点击节点的时候选中节点，默认值为 false，即只有在点击复选框时才会选中节点。, 默认值: false
 * @method $this autoExpandParent(bool $autoExpandParent = true) 展开子节点的时候是否自动展开父节点, 默认值: true
 * @method $this showCheckbox(bool $showCheckbox = true) 节点是否可被选择, 默认值: false
 * @method $this checkStrictly(bool $checkStrictly = true) 在显示复选框的情况下，是否严格的遵循父子不互相关联的做法，默认为 false, 默认值: false
 * @method $this accordion(bool $accordion) 是否每次只打开一个同级树节点展开, 默认值: false
 * @method $this indent(float $indent = true) 相邻级节点间的水平缩进，单位为像素, 默认值: 16
 * @method $this iconClass(string $iconClass) 自定义树节点的图标
 * @method $this draggable(bool $draggable) 是否开启拖拽节点功能, 默认值: false
 */
class Tree extends FormComponent
{
    /**
     * 选中
     */
    const TYPE_SELECTED = 'selected';
    /**
     * 选择
     */
    const TYPE_CHECKED = 'checked';


    protected bool $selectComponent = true;

    protected array $defaultProps = [
        'type' => self::TYPE_CHECKED,
        'showCheckbox' => true,
        'data' => []
    ];

    protected static array $propsRule = [
        'type' => 'string',
        'emptyText' => 'string',
        'nodeKey' => 'string',
        'props' => 'array',
        'renderAfterExpand' => 'bool',
        'highlightCurrent' => 'bool',
        'defaultExpandAll' => 'bool',
        'expandOnClickNode' => 'bool',
        'checkOnClickNode' => 'bool',
        'autoExpandParent' => 'bool',
        'showCheckbox' => 'bool',
        'checkStrictly' => 'bool',
        'accordion' => 'bool',
        'indent' => 'float',
        'iconClass' => 'string',
        'draggable' => 'bool',
    ];

    /**
     * @param array|Collection $treeData
     * @return $this
     */
    public function data($treeData): Tree
    {
        if ($treeData instanceof Collection) {
            $treeData = $treeData->toTree();
        }

        $this->props['data'] = [];

        foreach ($treeData as $child) {
            $this->props['data'][] = $child instanceof TreeData
                ? $child->getOption()
                : $child;
        }

        return $this;
    }

    /**
     * @param string $var
     * @return $this
     */
    public function jsData(string $var): Tree
    {
        $this->props['data'] = 'js.' . (string)$var;
        return $this;
    }

    public function createValidate()
    {
        return ValidateFactory::validateArr();
    }

    /**
     *
     * @time 2021年08月14日
     * @param $label
     * @param $value
     * @return $this
     */
    public function label($label, $value): self
    {
        $this->props([
            'props' => [
                'label' => $label,
                'value' => $value
            ]
        ]);

        return $this;
    }
}

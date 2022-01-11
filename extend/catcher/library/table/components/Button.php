<?php

namespace catcher\library\table\components;

class Button extends Component
{
    protected $el = 'button';

    /**
     * icon
     *
     * @time 2021年05月07日
     * @param string $icon
     * @return $this
     */
    public function icon(string $icon): self
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    /**
     * 文字
     *
     * @time 2021年05月07日
     * @param string $text
     * @return $this
     */
    public function text(string $text): self
    {
        $this->attributes['label'] = $text;

        return $this;
    }

    /**
     * 样式
     *
     * @time 2021年05月07日
     * @param string $style
     * @return $this
     */
    public function style(string $style): self
    {
        $this->attributes['class'] = $style;

        return $this;
    }

    /**
     * 点击事件
     *
     * @time 2021年05月07日
     * @param string $click
     * @return $this
     */
    public function click(string $click): self
    {
        $this->attributes['click'] = $click;

        return $this;
    }

    /**
     * 权限 action 指令
     *
     * @time 2021年05月07日
     * @param string $action
     * @return $this
     */
    public function permission(string $permission): self
    {
        $this->attributes['permission'] = $permission;

        return $this;
    }

    /**
     * 支持路由跳转
     *
     * @time 2021年04月28日
     * @param string $route
     * @return $this
     */
    public function to(string $route): self
    {
        $this->attributes['route'] = $route;

        return $this;
    }
}

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
namespace catch\support\form\fields\relation;

use catch\support\form\element\components\Group;

class HasOne extends Group
{
    /**
     *
     * @param string $name
     * @param string $title
     * @return HasOne
     */
    public static function make(string $name, string $title): HasOne
    {
        $hasMany = new self($name, $title);

        return $hasMany->attr('relation', 'hasOne');
    }

    /**
     * as
     *
     * @param string $field
     * @return HasOne
     */
    public function as(string $field): HasOne
    {
        $this->attr('as', $field);

        return $this;
    }


    /**
     *  label
     * @param $label
     * @param $value
     * @return HasOne
     */
    public function label($label, $value): HasOne
    {
        $this->attr('label', $label);

        $this->attr('value', $value);

        return $this;
    }

    /**
     * fields
     *
     * @param $fields
     * @return $this
     */
    public function fields( $fields): HasOne
    {
        if ($fields instanceof \Closure) {
            $fields = call_user_func($fields);
        }

        if (is_object($fields)) {
            $fields = $fields();
        }

        $this->rule($fields)->defaultValue($fields);

        return $this->min(1)->max(1);
    }


    /**
     * default value
     *
     * @param $fields
     * @return $this
     */
    public function defaultValue($fields): HasOne
    {
        $default = [];

        foreach ($fields as $field) {
            $default[$field->getField()] = '';
        }

        $this->default([$default]);

        return $this;
    }
}

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
namespace catch\support\form\fields;

class Select extends \catch\support\form\element\components\Select
{
    /**
     * make
     *
     * @time 2021年08月09日
     * @param string $name
     * @param string $title
     * @param null $options
     * @return Select
     */
    public static function make(string $name, string $title, $options = null): Select
    {
        $select = (new self($name, $title))->clearable()->filterable();

        if ($options) {
            return $select->options($options);
        }

        return $select;
    }

    /**
     * required
     *
     * @time 2021年08月14日
     * @param string|null $message
     * @return \catch\support\form\element\components\Select|Select
     */
    public function required(string $message = null)
    {
        if (is_string($this->getOptions()[0]['value'])) {
            return parent::required($message);
        }

        return parent::requiredNum($message);
    }
}

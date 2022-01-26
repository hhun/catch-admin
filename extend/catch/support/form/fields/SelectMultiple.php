<?php
namespace catch\support\form\fields;

use catch\support\form\element\components\Select;
use think\model\Collection;

class SelectMultiple extends Select
{
    /**
     * make
     *
     * @time 2021年08月09日
     * @param string $name
     * @param string $title
     * @param array|Collection $options
     * @return Select
     */
    public static function make(string $name, string $title, $options = null): Select
    {
        $multiple = new self($name, $title);

        if ($options) {
            return $multiple->multiple()->options($options);
        }

        return $multiple->multiple()->clearable();
    }
}

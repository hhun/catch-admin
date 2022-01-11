<?php
namespace catchAdmin\cms\tables\forms;

use catchAdmin\cms\model\Category as CategoryModel;
use catchAdmin\cms\support\DynamicFormFields;
use catcher\library\form\Form;
use catcher\Utils;

abstract class BaseForm extends Form
{
    protected ?string $table = null;

    /**
     * @desc create
     *
     * @time 2022年01月11日
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create(): array
    {
        $fields = parent::create();

        if ($this->table) {
            return array_merge($fields, (new DynamicFormFields())->build(Utils::tableWithPrefix($this->table)));
        }

        return $fields;
    }
}
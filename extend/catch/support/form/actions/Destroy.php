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

namespace catch\support\form\actions;

use Catcher\Exceptions\FailedException;
use catch\support\form\actions\relation\DestroyRelation;

class Destroy extends Action
{
    use DestroyRelation;

    const PARENT_ID = 'parent_id';

    public function deal(): bool
    {
        $condition = $this->form->getCondition();


        $this->model = $this->model->where($condition)->find();

        if (! $this->model) {
            throw new FailedException('Data Is Not Exist');
        }

        // is Model has Children
        if (! $this->form->getBeforeDestroy() &&
            in_array(self::PARENT_ID, array_column($this->fields, 'field')) &&
            $this->model->where('parent_id', $condition[$this->model->getPk()])->first()
        ) {
            throw new FailedException('包含子级, 无法删除');
        }

        // delete model
        if (! $this->model->delete()) {
            throw new FailedException('Deleted Failed');
        }

        return true;
    }
}

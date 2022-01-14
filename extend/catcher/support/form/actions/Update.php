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

namespace catcher\support\form\actions;

use Catcher\Exceptions\FailedException;
use catcher\support\form\actions\relation\UpdateRelation;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Update extends Action
{
    use UpdateRelation;

    /**
     * deal form data
     *
     * @time 2021年08月24日
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function deal(): bool
    {
        $this->model = $this->model->where($this->form->getCondition())->find();

        if (! $this->model) {
            throw new FailedException('Data Is Not Exist');
        }

        $fillAble = $this->model->getFillable();

        foreach ($this->form as $field => $value) {
            if (in_array($field, $fillAble) && $value) {
                $this->model->{$field} = $value;
            }
        }

        if (! $this->model->save()) {
            throw new FailedException('Update Failed');
        }

        return true;
    }
}

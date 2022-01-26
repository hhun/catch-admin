<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace catch\traits\db;

use catchAdmin\permissions\model\Users;

trait ScopeTrait
{
    /**
     * 创建人
     *
     * @time 2020年06月17日
     * @param $query
     * @return mixed
     */
    public function scopeCreator($query)
    {
        if (property_exists($this, 'field') && in_array('creator_id', $this->field)) {
            return $query->addSelectSub(function () {
                $user = app(Users::class);
                return $user->whereColumn($this->getTable().'.creator_id', $user->getTable().'.id')
                    ->field('username');
            }, 'creator');
        }

        return $query;
    }
}

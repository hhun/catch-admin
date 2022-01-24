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

namespace catchAdmin\permissions\model;

trait HasRolesTrait
{
    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_has_roles', 'role_id', 'uid');
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $fields
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles()->select();
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $roles
     * @return mixed
     */
    public function attachRoles(array $roles)
    {
        if (empty($roles)) {
            return true;
        }

        sort($roles);

        return $this->roles()->attach($roles);
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $roles
     * @return mixed
     */
    public function detachRoles(array $roles = [])
    {
        if (empty($roles)) {
            return $this->roles()->detach();
        }

        return $this->roles()->detach($roles);
    }
}

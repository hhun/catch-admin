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

trait HasPermissionsTrait
{
    /**
     *
     * @time 2019年12月09日
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $condition
     * @param array $field
     * @return mixed
     */
    public function getPermissions($condition = [], $field = [])
    {
        return $this->permissions()
      ->when(! empty($field), function ($query) use ($field) {
          $query->field($field);
      })
      ->when(! empty($condition), function ($query) use ($condition) {
          $query->where($condition);
      })
      ->select();
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $permissions
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function attachPermissions(array $permissions)
    {
        if (empty($permissions)) {
            return true;
        }

        sort($permissions);

        return $this->permissions()->attach($permissions);
    }

    /**
     *
     * @time 2019年12月08日
     * @param array $permissions
     * @return mixed
     */
    public function detachPermissions(array $permissions = [])
    {
        if (empty($permissions)) {
            return $this->permissions()->detach();
        }

        return $this->permissions()->detach($permissions);
    }
}

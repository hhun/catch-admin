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

use catchAdmin\permissions\model\search\RolesSearch;
use catch\base\CatchModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\relation\BelongsToMany;

class Roles extends CatchModel
{
    use HasDepartmentsTrait;
    use HasPermissionsTrait;
    use RolesSearch;

    protected $name = 'roles';

    public const ALL_DATA = 1; // 全部数据
    public const SELF_CHOOSE = 2; // 自定义数据
    public const SELF_DATA = 3; // 本人数据
    public const DEPARTMENT_DATA = 4; // 部门数据
    public const DEPARTMENT_DOWN_DATA = 5; // 部门及以下数据

    protected $field = [
        'id', //
        'role_name', // 角色名
        'identify', // 身份标识
        'parent_id', // 父级ID
        'creator_id', // 创建者
        'data_range', // 数据范围
        'description', // 角色备注
        'created_at', // 创建时间
        'updated_at', // 更新时间
        'deleted_at', // 删除状态，0未删除 >0 已删除

    ];

    /**
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getList()
    {
        return $this->catchSearch()
                    ->with(['permissions', 'departments'])
                    ->orderDesc('id')
                    ->select()
                    ->each(function (&$item) {
                        $permissions = $item->permissions->column('id');
                        unset($item['permissions']);
                        $item['_permissions'] = $permissions;

                        $departments = $item->departments->column('id');
                        unset($item['departments']);
                        $item['departments'] = $departments;
                    })
                    ->toTree();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Users::class, 'user_has_roles', 'uid', 'role_id');
    }
}

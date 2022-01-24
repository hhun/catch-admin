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

namespace catchAdmin\permissions\controller;

use catchAdmin\permissions\model\Permissions;
use catchAdmin\permissions\model\Roles as RoleModel;
use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class Role extends CatchController
{
    protected RoleModel $role;

    public function __construct(RoleModel $role)
    {
        $this->role = $role;
    }

    /**
     * @return Json
     */
    public function index(): Json
    {
        return CatchResponse::success($this->role->getList());
    }

    /**
     * @param Request $request
     * @return Json
     * @throws DbException
     */
    public function save(Request $request): Json
    {
        $params = $request->param();

        if ($this->role::where('identify', $params['identify'])->find()) {
            throw new FailedException('角色标识 ['.$params['identify'].']已存在');
        }

        $this->role->storeBy($params);
        // 分配权限
        if (count($params['permissions'])) {
            $this->role->attachPermissions(array_unique($params['permissions']));
        }
        // 分配部门
        if (isset($params['departments']) && count($params['departments'])) {
            $this->role->attachDepartments($params['departments']);
        }
        // 添加角色
        return CatchResponse::success();
    }

    /**
     * @param $id
     * @return Json
     */
    public function read($id): Json
    {
        /* @var RoleModel $role */
        $role = $this->role->findBy($id);

        $role->permissions = $role->getPermissions();
        $role->departments = $role->getDepartments();

        return CatchResponse::success($role);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Json
     * @throws DbException
     */
    public function update($id, Request $request): Json
    {
        if (Roles::where('identify', $request->param('identify'))->where('id', '<>', $id)->find()) {
            throw new FailedException('角色标识 ['.$request->param('identify').']已存在');
        }

        $this->role->updateBy($id, $request->param());

        /* @var RoleModel $role */
        $role = $this->role->findBy($id);

        $hasPermissionIds = $role->getPermissions()->column('id');

        $permissionIds = $request->param('permissions');

        // 已存在权限 IDS
        $existedPermissionIds = [];
        foreach ($hasPermissionIds as $hasPermissionId) {
            if (in_array($hasPermissionId, $permissionIds)) {
                $existedPermissionIds[] = $hasPermissionId;
            }
        }

        $attachIds = array_diff($permissionIds, $existedPermissionIds);
        $detachIds = array_diff($hasPermissionIds, $existedPermissionIds);

        if (! empty($detachIds)) {
            $role->detachPermissions($detachIds);
        }
        if (! empty($attachIds)) {
            $role->attachPermissions(array_unique($attachIds));
        }

        // 更新department
        $hasDepartmentIds = $role->getDepartments()->column('id');
        $departmentIds = $request->param('departments', []);

        // 已存在部门 IDS
        $existedDepartmentIds = [];
        foreach ($hasDepartmentIds as $hasDepartmentId) {
            if (in_array($hasDepartmentId, $departmentIds)) {
                $existedDepartmentIds[] = $hasDepartmentId;
            }
        }

        $attachDepartmentIds = array_diff($departmentIds, $existedDepartmentIds);
        $detachDepartmentIds = array_diff($hasDepartmentIds, $existedDepartmentIds);

        if (! empty($detachDepartmentIds)) {
            $role->detachDepartments($detachDepartmentIds);
        }
        if (! empty($attachDepartmentIds)) {
            $role->attachDepartments(array_unique($attachDepartmentIds));
        }

        return CatchResponse::success([]);
    }

    /**
     * @param $id
     * @throws FailedException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @return Json
     */
    public function delete($id): Json
    {
        if ($this->role->where('parent_id', $id)->find()) {
            throw new FailedException('存在子角色，无法删除');
        }
        /* @var RoleModel $role */
        $role = $this->role->findBy($id);

        // 删除权限
        $role->detachPermissions();
        // 删除部门关联
        $role->detachDepartments();
        // 删除用户关联
        $role->users()->detach();
        // 删除
        $this->role->deleteBy($id);

        return CatchResponse::success([]);
    }

    /**
     * 获取角色权限
     *
     * @param $id
     * @return Json
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function getPermissions($id): Json
    {
        $permissionIds = $this->role->findBy($id)->getPermissions()->column('id');

        if (! count($permissionIds)) {
            $permissions = Permissions::field(['id', 'parent_id', 'permission_name'])->select()->toTree();
        } else {
            $permissions = Permissions::whereIn('id', $permissionIds)->field(['id', 'parent_id', 'permission_name'])->select()->toTree();
        }

        return CatchResponse::success($permissions);
    }
}

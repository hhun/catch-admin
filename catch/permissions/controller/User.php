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

use catchAdmin\permissions\excel\UserExport;
use catchAdmin\permissions\model\Permissions;
use catchAdmin\permissions\model\Roles;
use catchAdmin\permissions\model\Users;
use catchAdmin\permissions\request\CreateRequest;
use catchAdmin\permissions\request\UpdateRequest;
use catchAdmin\permissions\request\ProfileRequest;
use catch\base\CatchController;
use catch\CatchAuth;
use catch\CatchCacheKeys;
use catch\CatchResponse;
use catch\enums\Status;
use catch\library\excel\Excel;
use catch\Utils;
use think\db\exception\DbException;
use think\facade\Cache;
use think\response\Json;

class User extends CatchController
{
    protected Users $user;

    public function __construct(Users $user)
    {
        $this->user = $user;
    }

    /**
     * @throws DbException
     * @return Json
     */
    public function index()
    {
        return CatchResponse::paginate($this->user->getList());
    }

    /**
     * 获取用户信息
     *
     * @time 2020年01月07日
     * @param CatchAuth $auth
     * @throws \think\db\exception\DataNotFoundException
     * @throws DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return Json
     */
    public function info(CatchAuth $auth)
    {
        $user = $auth->user();

        $roles = $user->getRoles()->column('identify');

        $permissionIds = $user->getPermissionsBy($user->id);
        // 缓存用户权限
        Cache::set(CatchCacheKeys::USER_PERMISSIONS.$user->id, $permissionIds);

        $user->permissions = Permissions::getCurrentUserPermissions($permissionIds);

        $user->roles = $roles;

        // 用户数据权限
        // $user->data_range = Roles::getDepartmentUserIdsBy($roles);

        return CatchResponse::success($user);
    }

    /**
     *
     * @param CreateRequest $request
     * @time 2019年12月06日
     * @return Json
     */
    public function save(CreateRequest $request)
    {
        $this->user->storeBy($request->param());

        $this->user->attachRoles($request->param('roles'));

        if ($request->param('jobs')) {
            $this->user->attachJobs($request->param('jobs'));
        }

        return CatchResponse::success('', '添加成功');
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return Json
     */
    public function read($id)
    {
        $user = $this->user->findBy($id);
        $user->roles = $user->getRoles();
        $user->jobs = $user->getJobs();
        return CatchResponse::success($user);
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @param UpdateRequest $request
     * @return Json
     */
    public function update($id, UpdateRequest $request)
    {
        $this->user->updateBy($id, $request->filterEmptyField()->param());

        $user = $this->user->findBy($id);

        $user->detachRoles();
        $user->detachJobs();

        if (! empty($request->param('roles'))) {
            $user->attachRoles($request->param('roles'));
        }
        if (! empty($request->param('jobs'))) {
            $user->attachJobs($request->param('jobs'));
        }
        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return Json
     */
    public function delete($id)
    {
        $ids = Utils::stringToArrayBy($id);

        foreach ($ids as $_id) {
            $user = $this->user->findBy($_id);
            // 删除角色
            $user->detachRoles();
            // 删除岗位
            $user->detachJobs();

            $this->user->deleteBy($_id);
        }

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月07日
     * @param $id
     * @return Json
     */
    public function switchStatus($id): Json
    {
        $ids = Utils::stringToArrayBy($id);

        foreach ($ids as $_id) {
            $user = $this->user->findBy($_id);

            $this->user->updateBy($_id, [
                'status' => $user->status == Status::Enable ? Status::Disable : Status::Enable,
            ]);
        }

        return CatchResponse::success([], '操作成功');
    }

    /**
     * 导出
     *
     * @time 2020年09月08日
     * @param Excel $excel
     * @param UserExport $userExport
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @return Json
     */
    public function export(Excel $excel, UserExport $userExport)
    {
        return CatchResponse::success($excel->save($userExport, Utils::publicPath('export/users')));
    }

    /**
     * 更新个人信息
     *
     * @time 2020年09月20日
     * @param ProfileRequest $request
     * @return Json
     */
    public function profile(ProfileRequest $request)
    {
        return CatchResponse::success($this->user->updateBy($request->user()->id, $request->filterEmptyField()->param()));
    }
}

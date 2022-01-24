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

use catcher\base\CatchController;
use catchAdmin\permissions\model\Department as DepartmentModel;
use catcher\base\CatchRequest;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class Department extends CatchController
{
    protected DepartmentModel $department;

    public function __construct(DepartmentModel $department)
    {
        $this->department = $department;
    }

    /**
     * 列表
     *
     * @return Json
     * @throws DbException
     */
    public function index(): Json
    {
        return CatchResponse::success($this->department->getList());
    }

    /**
     * 保存
     *
     * @param CatchRequest $request
     * @return Json
     */
    public function save(CatchRequest $request): Json
    {
        return CatchResponse::success($this->department->storeBy($request->param()));
    }

    /**
     * 更新
     *
     * @param $id
     * @param CatchRequest $request
     * @return Json
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function update($id, CatchRequest $request): Json
    {
        return CatchResponse::success($this->department->updateBy($id, $request->param()));
    }

    /**
     * 删除
     *
     * @param $id
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function delete($id): Json
    {
        if ($this->department->where('parent_id', $id)->find()) {
            throw new FailedException('存在子部门，无法删除');
        }

        return CatchResponse::success($this->department->deleteBy($id));
    }
}

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

use catchAdmin\permissions\model\Job as JobModel;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class Job extends CatchController
{
    protected JobModel $job;

    public function __construct(JobModel $job)
    {
        $this->job = $job;
    }

    /**
     * 列表
     *
     * @return Json
     * @throws DbException
     */
    public function index(): Json
    {
        return CatchResponse::paginate($this->job->getList());
    }

    /**
     * 保存
     *
     * @param CatchRequest $request
     * @return Json
     */
    public function save(CatchRequest $request): Json
    {
        return CatchResponse::success($this->job->storeBy($request->post()));
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
        return CatchResponse::success($this->job->updateBy($id, $request->post()));
    }

    /**
     * 删除
     *
     * @param $id
     * @return Json
     */
    public function delete($id): Json
    {
        return CatchResponse::success($this->job->deleteBy($id));
    }

    /**
     * 获取所有
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAll(): Json
    {
        return CatchResponse::success($this->job->field(['id', 'job_name'])->select());
    }
}

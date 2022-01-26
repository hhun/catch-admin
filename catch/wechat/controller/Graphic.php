<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\wechat\controller;

use catchAdmin\wechat\repository\WechatGraphicRepository;
use catch\base\CatchController;
use catch\base\CatchRequest;
use catch\CatchResponse;

class Graphic extends CatchController
{
    protected $graphic;

    public function __construct(WechatGraphicRepository $graphic)
    {
        $this->graphic = $graphic;
    }

    /**
     * list
     *
     * @time 2020年06月27日
     * @param CatchRequest $request
     * @return \think\response\Json
     */
    public function index(CatchRequest $request)
    {
        return CatchResponse::paginate($this->graphic->getList($request->param()));
    }

    public function read($id)
    {
        return CatchResponse::success($this->graphic->findBy($id));
    }

    public function save(CatchRequest $request)
    {
        return CatchResponse::success($this->graphic->storeBy($request->param()));
    }

    public function update($id, CatchRequest $request)
    {
        return CatchResponse::success($this->graphic->updateBy($id, $request->param()));
    }

    public function delete($id)
    {
        return CatchResponse::success($this->graphic->deleteBy($id));
    }
}

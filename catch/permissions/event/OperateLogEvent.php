<?php

namespace catchAdmin\permissions\event;

use catchAdmin\permissions\model\Permissions;
use catchAdmin\system\model\OperateLog;
use catch\Utils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Request;
use think\Response;

/**
 * 操作日志记录
 */
class OperateLogEvent
{
    /**
     * @desc 记录操作
     *
     * @time 2022年01月14日
     * @param Response $response
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function handle(Response $response)
    {
        try {
            /* @var Request $request */
            $request = app(Request::class);

            $rule = $request->rule()->getName();
            // 模块忽略
            [$module, $controller, $action] = Utils::parseRule($rule);
            $permission = $this->getPermission($module, $controller, $action);
            if (!$permission) {
                return;
            }

            $creatorId = $request->user()->id;
            $parentPermission = Permissions::where('id', $permission->parent_id)->value('permission_name');
            $requestParams = $request->param();
            // 如果参数过长则不记录
            if (!empty($requestParams) && mb_strlen(\json_encode($requestParams)) > 1000) {
                $requestParams = [];
            }

            app(OperateLog::class)->storeBy([
                'creator_id' => $creatorId,
                'module' => $parentPermission ?: '',
                'method' => $request->method(),
                'operate' => $permission->permission_name,
                'route' => $permission->permission_mark,
                'params' => !empty($requestParams) ? json_encode($requestParams, JSON_UNESCAPED_UNICODE) : '',
                'created_at' => time(),
                'ip' => $request->ip(),
            ]);
        } catch (\Exception $e) {
        }
    }

    /**
     * @desc get permission
     * @time 2022年01月14日
     * @param $module
     * @param $controllerName
     * @param $action
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    protected function getPermission($module, $controllerName, $action): mixed
    {
        $permissionMark = sprintf('%s@%s', $controllerName, $action);

        return Permissions::where('module', $module)->where('permission_mark', $permissionMark)->find();
    }
}

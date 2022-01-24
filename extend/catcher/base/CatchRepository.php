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

namespace catcher\base;

use catcher\exceptions\FailedException;

/**
 * @method   getList(array $data = [])
 * @method   storeBy(array $data)
 * @method   updateBy(int $id, array $data)
 * @method   findBy(int $id, array $column = ['*'])
 * @method   deleteBy(int $id)
 * @method   disOrEnable(int $id)
 * @method   startTrans()
 * @method   rollback()
 * @method   commit()
 * @method   transaction(\Closure $callback)
 * @method   raw($sql)
 */
abstract class CatchRepository
{
    /**
     * 模型映射方法
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws FailedException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, 'model')) {
            return call_user_func_array([$this->model(), $name], $arguments);
        }

        throw new FailedException(sprintf('Method %s Not Found~', $name));
    }
}

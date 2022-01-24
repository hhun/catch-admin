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

namespace catcher;

use catcher\library\table\Table;

abstract class CatchTable
{
    abstract protected function table();


    abstract protected function form();

    /**
     * 渲染
     *
     * @time 2021年03月29日
     * @param $only  => form || table
     * @return array|\think\response\Json
     */
    public function render($only)
    {
        if ($only) {
            return CatchResponse::success([
                $only => $this->{$only}()
            ]);
        }

        return CatchResponse::success([
            'table' => $this->table(),
            'form' => $this->form()
        ]);
    }

    /**
     * 获取表对象
     *
     * @time 2021年03月30日
     * @param string $tableName
     * @return Table
     */
    protected function getTable(string $tableName): Table
    {
        return new Table($tableName);
    }
}

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

use catchAdmin\migration\Migrator;
use catchAdmin\migration\builder\Scheme;
use catchAdmin\migration\builder\Table;

class Roles extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        Scheme::create('roles', function (Table $table){
            $table->id();

            $table->string('role_name', 15)->default('')->comment('角色名称');

            $table->string('identify', 20)->default('')->comment('角色的标识，用英文表示，用于后台路由权限');

            $table->integer('parent_id')->default(0)->comment('父级ID');

            $table->string('description')->default('')->comment('角色备注');

            $table->tinyInteger('data_range')->default(0)->comment('1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据');

            $table->integer('creator_id')->default(0)->comment('创建人ID');

            $table->timestamps();

            $table->softDelete();
        });
    }

    public function down()
    {
       Scheme::dropIfExist('roles');
    }
}

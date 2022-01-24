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

class Permissions extends Migrator
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
        Scheme::create('permissions', function (Table $table){
            $table->id();

            $table->string('permission_name', 15)->default('')->comment('菜单名称');

            $table->integer('parent_id')->default(0)->comment('父级ID');

            $table->string('level', 50)->default('')->comment('层级');

            $table->string('route', 50)->default('')->comment('路由');

            $table->string('icon', 50)->default('')->comment('菜单图标');

            $table->string('module', 20)->default('')->comment('模块名称');

            $table->integer('creator_id')->default(0)->comment('创建人ID');

            $table->string('permission_mark', 50)->default('')->nullable(false)->comment('权限标识别');

            $table->string('component')->default('')->comment('组件名称');

            $table->string('redirect')->default('')->comment('跳转地址');

            $table->tinyInteger('keepalive')->default(1)->comment('1 缓存 2 不存在');

            $table->tinyInteger('type')->default(1)->comment('1 菜单 2 按钮');

            $table->tinyInteger('hidden')->default(1)->comment('1 显示 2 隐藏');

            $table->integer('sort')->default(0)->comment('排序字段');

            $table->timestamps();

            $table->softDelete();

            $table->comment('菜单表');
        });
    }

    public function down()
    {
        Scheme::dropIfExist('permissions');
    }
}

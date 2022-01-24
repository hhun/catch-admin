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

class Department extends Migrator
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
        Scheme::create('departments', function (Table $table){
            $table->id();

            $table->string('department_name', 15)->default('')->comment('部门名称');

            $table->integer('parent_id')->default(0)->comment('父级ID');

            $table->string('principal', 20)->default('')->comment('负责人');

            $table->string('mobile', 20)->default('')->comment('负责人电话');

            $table->string('email', 100)->default('')->comment('联系人邮箱');

            $table->integer('creator_id')->default(0)->comment('创建人ID');

            $table->tinyInteger('status')->default(1)->comment('1 正常 2 停用');

            $table->integer('sort')->default(0)->comment('排序字段');

            $table->timestamps();

            $table->softDelete();

            $table->comment('部门表');
        });
    }

    public function down()
    {
        Scheme::dropIfExist('departments');
    }
}

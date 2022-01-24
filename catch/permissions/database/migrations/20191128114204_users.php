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

class Users extends Migrator
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
        Scheme::create('users', function (Table $table){
            $table->id();

            $table->string('user_name', 15)->default('')->comment('用户名');

            $table->string('password')->comment('用户密码');

            $table->string('email', 100)->comment('邮箱');

            $table->string('avatar')->comment('头像')->default('');

            $table->string('remember_token', 512)->comment('token')->default('');

            $table->integer('creator_id')->default(0)->comment('创建人ID');

            $table->integer('department_id')->default(0)->comment('部门ID');

            $table->boolean('status')->default(1)->comment('用户状态 1 正常 2 禁用');

            $table->string('last_login_ip', 30)->default('')->comment('最后登陆的IP');

            $table->timestamps();

            $table->softDelete();

            $table->comment('用户表');
        });
    }

    public function down()
    {
        Scheme::dropIfExist('users');
    }
}

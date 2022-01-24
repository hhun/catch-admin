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

class RoleHasPermissions extends Migrator
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
        Scheme::create('role_has_permissions', function (Table $table){
           $table->unsignedInteger('role_id') ->comment('角色ID');

           $table->unsignedInteger('permission_id')->comment('权限ID');

           $table->comment('角色权限表');
        });
    }

    public function down()
    {
        Scheme::dropIfExist('role_haas_permissions');
    }
}

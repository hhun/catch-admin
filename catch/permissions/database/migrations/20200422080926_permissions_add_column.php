<?php

use think\migration\Migrator;

class PermissionsAddColumn extends Migrator
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
    public function change()
    {
        if ($this->hasTable('permissions')) {
            $table = $this->table('permissions');

            $table->addColumn('level', 'string', ['default' => '', 'comment' => '层级', 'limit' => '50', 'after' => 'parent_id'])
                ->update();
        }
    }
}

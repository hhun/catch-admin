<?php
/**
 * @filename  GetModuleTrait.php
 * @createdAt 2020/2/24
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */

namespace catchAdmin\wechat\command;

use catchAdmin\wechat\library\SyncWechatUsers;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class SyncUsersCommand extends Command
{
    protected $officialAccount;

    public function configure()
    {
        $this->setName('sync:users')
            ->setDescription('sync wechat users');
    }

    /**
     *
     * @time 2020年07月19日
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    public function execute(Input $input, Output $output)
    {
        (new SyncWechatUsers())->start();
    }
}

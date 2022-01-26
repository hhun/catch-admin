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

namespace catch\command\install;

use catch\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class ModuleServiceDiscoverCommand extends Command
{
    protected function configure()
    {
        $this->setName('catch-service:discover')
            ->addOption('module', '-m', Option::VALUE_REQUIRED, 'module name')
            ->setDescription('install catch module service');
    }

    protected function execute(Input $input, Output $output)
    {
        $module = $input->getOption('module');

        $moduleServices = $this->getServices($module);

        $services = [];
        $servicesPath = root_path().'vendor'.DIRECTORY_SEPARATOR.'services.php';
        if (file_exists($servicesPath)) {
            $services = include $servicesPath;
        }

        $services = array_unique(array_merge($services, $moduleServices));

        $this->exportServices($services, $servicesPath);
    }

    /**
     * 导出服务
     *
     * @time 2020年06月20日
     * @param $services
     * @param $servicesPath
     * @return void
     */
    protected function exportServices($services, $servicesPath)
    {
        $exportArr = var_export($services, true);

        $currentTime = date('Y-m-d H:i:s');

        file_put_contents(
            $servicesPath,
            <<<PHP
<?php 
// This file is automatically generated at:{$currentTime}
declare (strict_types = 1);

return $exportArr;
PHP
        );
    }
    /**
     * 获取服务
     *
     * @time 2020年06月20日
     * @param $module
     * @return array
     */
    protected function getServices($module)
    {
        if ($module) {
            $moduleInfo = CatchAdmin::getModuleInfo(CatchAdmin::directory().$module);
            if (isset($moduleInfo['services']) && ! empty($moduleInfo['services'])) {
                return $moduleInfo['enable'] ? $moduleInfo['services'] : [];
            } else {
                return [];
            }
        }

        return CatchAdmin::getEnabledService();
    }

    /**
     * 获取模块
     *
     * @time 2020年06月20日
     * @param $module
     * @return array
     */
    protected function getModules($module)
    {
        $moduleNames = [];

        if (! $module) {
            $modules = CatchAdmin::getModulesDirectory();
            foreach ($modules as $module) {
                $m = explode(DIRECTORY_SEPARATOR, trim($module, DIRECTORY_SEPARATOR));
                $moduleNames[] = array_pop($m);
            }

            return $moduleNames;
        }

        return [$module];
    }
}

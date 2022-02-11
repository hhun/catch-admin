<?php

declare(strict_types=1);

namespace catch\command\Tools;

use catch\library\Compress;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * 打包模块
 *
 * Class CompressPackageCommand
 * @package catch\command\Tools
 */
class CompressPackageCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('catch:unpack ')
             ->addArgument('module', Argument::REQUIRED, 'module name')
             ->setDescription('compress module to zip');
    }

    protected function execute(Input $input, Output $output)
    {
        $package = $this->input->getArgument('module');

        try {
            (new Compress())->moduleToZip($package);
        } catch (\Exception $e) {
            $output->error($e->getMessage());
            exit;
        }

        $output->info($package.' zip successfully~');
    }
}

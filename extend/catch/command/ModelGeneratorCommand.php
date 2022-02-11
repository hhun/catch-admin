<?php

namespace catch\command;

use catch\CatchAdmin;
use catch\generate\factory\Model;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\helper\Str;

class ModelGeneratorCommand extends Command
{
    protected function configure()
    {
        $this->setName('create:model')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->addArgument('model', Argument::REQUIRED, 'model name')
            ->addOption('softDelete', '-d', Option::VALUE_REQUIRED, 'soft delete')
            ->setDescription('create model');
    }

    protected function execute(Input $input, Output $output)
    {
        $model = ucfirst($input->getArgument('model'));
        $module = mb_strtolower($input->getArgument('module'));
        $softDelete = $input->getOption('softDelete');

        $params = [
            'model' => 'catchAdmin\\'.$module.'\\model\\'.$model,
            'table' => Str::snake($model),
            'extra' => [
                'soft_delete' => (bool)$softDelete,
            ],
        ];

        $modelFile = CatchAdmin::getModuleModelDirectory($module).$model.'.php';

        $asn = 'Y';

        if (file_exists($modelFile)) {
            $asn = $this->output->ask($this->input, "Model File {$model} already exists.Are you sure to overwrite, the content will be lost(Y/N)");
        }

        if (mb_strtolower($asn) == 'n') {
            exit(0);
        }

        (new Model())->done($params);

        if (file_exists($modelFile)) {
            $output->info(sprintf('%s Create Successfully!', $modelFile));
        } else {
            $output->error(sprintf('%s Create Failed!', $modelFile));
        }
    }
}

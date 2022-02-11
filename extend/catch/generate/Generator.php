<?php

namespace catch\generate;

use catch\exceptions\FailedException;
use catch\generate\factory\Controller;
use catch\generate\factory\Migration;
use catch\generate\factory\Model;
use catch\generate\factory\Route;
use catch\generate\factory\SQL;
use catch\generate\support\Table;
use catch\library\Composer;
use catch\Utils;
use JaguarJack\Generate\Exceptions\TypeNotFoundException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Generator
{
    public const NEED_PACKAGE = 'jaguarjack/file-generate';

    /**
     * generate
     *
     * @time 2020年04月29日
     * @param $params
     * @return array
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function done($params): array
    {
        // 判断是否安装了扩展包
        if (! (new Composer())->hasPackage(self::NEED_PACKAGE)) {
            throw new FailedException(
                sprintf('you must use [ composer require --dev %s:dev-master --ignore-platform-reqs]', self::NEED_PACKAGE)
            );
        }

        $params = \json_decode($params['data'], true);

        [$controller, $model] = $this->parseParams($params);

        $message = $files = [];

        $migration = '';

        try {
            if ($params['create_controller']) {
                $files[] = (new Controller())->done($controller);
                array_push($message, 'controller created successfully');
            }

            if ($params['create_table']) {
                (new SQL())->done($model);
                array_push($message, 'table created successfully');
            }

            if ($params['create_model']) {
                $files[] = (new Model())->done($model);
                array_push($message, 'model created successfully');
            }

            if ($params['create_migration']) {
                $migration = (new Migration())->done([$controller['module'], $model['table']]);
                array_push($message, 'migration created successfully');
            }

            // 只有创建了 Controller 最后成功才写入 route
            if ($params['create_controller']) {
                (new Route())->controller($controller['controller'])
                    ->restful($controller['restful'])
                    ->done();
            }
        } catch (\Throwable $exception) {
            if (! $exception instanceof TableExistException) {
                $this->rollback($files, $migration);
            }

            throw new FailedException($exception->getMessage());
        }

        return $message;
    }

    /**
     * preview
     *
     * @time 2020年04月29日
     * @param $params
     * @return string|string[]|void
     * @throws TypeNotFoundException
     */
    public function preview($params)
    {
        [$controller, $model] = $this->parseParams(\json_decode($params['data'], true));

        switch ($params['type']) {
            case 'controller':
                return (new Controller())->getContent($controller);
            case 'model':
                return (new Model())->getContent($model);
            default:
                break;
        }
    }


    /**
     * parse params
     *
     * @time 2020年04月28日
     * @param $params
     * @return array[]
     */
    protected function parseParams($params): array
    {
        $module = $params['controller']['module'] ?? false;

        if (! $module) {
            throw new FailedException('请设置模块');
        }

        $controller = [
            'module' => $module,
            'model' => $params['controller']['model'] ?? '',
            'controller' => $params['controller']['controller'] ?? '',
            'restful' => $params['controller']['restful'],
        ];

        $table = $params['controller']['table'] ?? '';

        if ($table) {
            $table = Utils::tableWithPrefix($table);
        }

        $model = [
            'table' => $table,
            'model' => $params['controller']['model'] ?? '',
            'sql' => $params['table_fields'],
            'extra' => $params['table_extra'],
        ];


        return [$controller, $model];
    }


    /**
     * 回滚
     *
     * @param $files
     * @param $migration
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    protected function rollback($files, $migration)
    {
        if (Table::exist()) {
            Table::drop();
        }

        foreach ($files as $file) {
            unlink($file);
        }

        if ($migration && unlink($migration)) {
            $migrationModel = new class extends \think\Model {
                protected $name = 'migrations';
            };

           $migrationModel->order('version', 'desc')->find()->delete();
        }
    }
}

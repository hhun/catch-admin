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

declare(strict_types=1);

namespace catch;

use catch\base\CatchModel;
use think\db\exception\DbException;
use think\db\Query;
use think\helper\Str;
use think\Paginator;

class CatchQuery extends Query
{
    /**
     *
     * @time 2020年01月13日
     * @param mixed $model
     * @param string $joinField
     * @param string $currentJoinField
     * @param array $field
     * @param string $type
     * @param array $bind
     * @return CatchQuery
     */
    public function catchJoin($model, string $joinField, string $currentJoinField, array $field = [], string $type = 'INNER', array $bind = []): self
    {
        $tableAlias = null;

        if (is_string($model)) {
            $table = app($model)->getTable();
        } else {
            list($model, $tableAlias) = $model;
            $table = app($model)->getTable();
        }

        // 合并字段
        $this->options['field'] = array_merge($this->options['field'] ?? [], array_map(function ($value) use ($table, $tableAlias) {
            return ($tableAlias ? : $table) . '.'. $value;
        }, $field));

        return $this->join($tableAlias ? sprintf('%s %s', $table, $tableAlias) : $table, sprintf('%s.%s=%s.%s', $tableAlias ?: $table, $joinField, $this->getAlias(), $currentJoinField), $type, $bind);
    }

    /**
     *
     * @time 2020年01月13日
     * @param mixed $model
     * @param string $joinField
     * @param string $currentJoinField
     * @param array $field
     * @param array $bind
     * @return CatchQuery
     */
    public function catchLeftJoin($model, string $joinField, string $currentJoinField, array $field = [], array $bind = []): self
    {
        return $this->catchJoin($model, $joinField, $currentJoinField, $field, 'LEFT', $bind);
    }

    /**
     *
     * @time 2020年01月13日
     * @param mixed $model
     * @param string $joinField
     * @param string $currentJoinField
     * @param array $field
     * @param array $bind
     * @return CatchQuery
     */
    public function catchRightJoin($model, string $joinField, string $currentJoinField, array $field = [], array $bind = []): self
    {
        return $this->catchJoin($model, $joinField, $currentJoinField, $field, 'RIGHT', $bind);
    }

    /**
     * rewrite
     *
     * @time 2020年01月13日
     * @param array|string $field
     * @param bool $needAlias
     * @return $this|Query
     */
    public function withoutField($field, bool $needAlias = false)
    {
        if (empty($field)) {
            return $this;
        }

        if (is_string($field)) {
            $field = array_map('trim', explode(',', $field));
        }

        // 过滤软删除字段
        $field[] = $this->model->getDeleteAtField();

        // 字段排除
        $fields = $this->getTableFields();
        $field = $fields ? array_diff($fields, $field) : $field;

        if (isset($this->options['field'])) {
            $field = array_merge((array) $this->options['field'], $field);
        }

        $this->options['field'] = array_unique($field);

        if ($needAlias) {
            $alias = $this->getAlias();
            $this->options['field'] = array_map(function ($field) use ($alias) {
                return $alias.'.'.$field;
            }, $this->options['field']);
        }

        return $this;
    }

    /**
     *
     * @param array $params
     * @return CatchQuery
     */
    public function catchSearch(array $params = []): self
    {
        $params = empty($params) ? \request()->param() : $params;

        if (empty($params)) {
            return $this;
        }

        foreach ($params as $field => $value) {
            $method = 'search'.Str::studly($field).'Attr';
            // value in [null, '']
            if ($value !== null && $value !== '' && method_exists($this->model, $method)) {
                $this->model->$method($this, $value, $params);
            }
        }

        return $this;
    }

    /**
     * 快速搜索
     *
     * @param array $params
     * @return Query
     */
    public function quickSearch(array $params = []): Query
    {
        $requestParams = \request()->param();

        if (empty($params) && empty($requestParams)) {
            return $this;
        }

        foreach ($requestParams as $field => $value) {
            if (isset($params[$field])) {
                // ['>', value] || value
                if (is_array($params[$field])) {
                    $this->where($field, $params[$field][0], $params[$field][1]);
                } else {
                    $this->where($field, $value);
                }
            } else {
                // 区间范围 start_数据库字段 & end_数据库字段
                $startPos = mb_strpos($field, 'start_');
                if ($startPos === 0) {
                    $this->where(str_replace('start_', '', $field), '>=', strtotime($value));
                }
                $endPos = mb_strpos($field, 'end_');
                if ($endPos === 0) {
                    $this->where(str_replace('end_', '', $field), '<=', strtotime($value));
                }
                // 模糊搜索
                if (Str::contains($field, 'like')) {
                    [$operate, $field] = explode('_', $field);
                    if ($operate === 'like') {
                        $this->whereLike($field, $value);
                    } elseif ($operate === '%like') {
                        $this->whereLeftLike($field, $value);
                    } else {
                        $this->whereRightLike($field, $value);
                    }
                }

                // = 值搜索
                if ($value || is_numeric($value)) {
                    if ($field != 'page' && $field != 'limit' && $startPos !== 0 && $endPos !== 0 && $operate !== 'like' && $operate !== '%like') {
                        $this->where($field, $value);
                    }
                }
            }
        }

        return $this;
    }

    /**
     *
     * @time 2020年01月13日
     * @return mixed
     */
    public function getAlias()
    {
        return isset($this->options['alias']) ? $this->options['alias'][$this->getTable()] : $this->getTable();
    }

    /**
     * rewrite
     *
     * @time 2020年01月13日
     * @param string $field
     * @param mixed $condition
     * @param string $option
     * @param string $logic
     * @return Query
     */
    public function whereLike(string $field, $condition, string $logic = 'AND', string $option = 'both'): Query
    {
        switch ($option) {
          case 'both':
              $condition = '%'.$condition.'%';
              break;
          case 'left':
              $condition = '%'.$condition;
              break;
          default:
              $condition .= '%';
        }

        if (mb_strpos($field, '.') === false) {
            $field = $this->getAlias().'.'.$field;
        }

        return parent::whereLike($field, $condition, $logic);
    }

    /**
     * @param string $field
     * @param $condition
     * @param string $logic
     * @return Query
     */
    public function whereLeftLike(string $field, $condition, string $logic = 'AND'): Query
    {
        return $this->where($field, $condition, $logic, 'left');
    }

    /**
     * @param string $field
     * @param $condition
     * @param string $logic
     * @return Query
     */
    public function whereRightLike(string $field, $condition, string $logic = 'AND'): Query
    {
        return $this->where($field, $condition, $logic, 'right');
    }

    /**
     * 额外的字段
     *
     * @time 2020年01月13日
     * @param $fields
     * @return CatchQuery
     */
    public function addFields($fields): self
    {
        if (is_string($fields)) {
            $this->options['field'][] = $fields;

            return $this;
        }

        $this->options['field'] = array_merge($this->options['field'], $fields);

        return $this;
    }

    /**
     *
     * @param null $listRows
     * @param false $simple
     * @return Paginator
     * @throws DbException
     */
    public function paginate($listRows = null, $simple = false): Paginator
    {
        if (!$listRows) {
            $limit = \request()->param('limit');

            $listRows = $limit ?: CatchModel::LIMIT;
        }

        return parent::paginate($listRows, $simple);
    }


    /**
     * 默认排序
     *
     * @time 2020年06月17日
     * @param string $order
     * @return $this
     */
    public function catchOrder(string $order = 'desc'): self
    {
        if (in_array('sort', array_keys($this->getFields()))) {
            $this->order($this->getTable().'.sort', $order);
        }

        if (in_array('weight', array_keys($this->getFields()))) {
            $this->order($this->getTable().'.weight', $order);
        }

        $this->order($this->getTable().'.'.$this->getPk(), $order);

        return $this;
    }

    /**
     * @param array|string $field
     * @return CatchQuery
     */
    public function orderDesc($field): CatchQuery
    {
        if (is_string($field)) {
            return $this->order($field, 'desc');
        }

        $orderFields = [];

        foreach ($field as $f) {
            $orderFields[$f] = 'desc';
        }

        return $this->order($orderFields);
    }

    /**
     * 新增 Select 子查询
     *
     * @time 2020年06月17日
     * @param callable $callable
     * @param string $as
     * @return $this
     */
    public function addSelectSub(callable $callable, string $as): self
    {
        $this->field(sprintf('%s as %s', $callable()->buildSql(), $as));

        return $this;
    }

    /**
     * 字段增加
     *
     * @time 2020年11月04日
     * @param $field
     * @param int $amount
     * @return int
     *@throws DbException
     */
    public function increment($field, int $amount = 1): int
    {
        return $this->inc($field, $amount)->update();
    }

    /**
     * 字段减少
     *
     * @time 2020年11月04日
     * @param $field
     * @param int $amount
     * @return int
     *@throws DbException
     */
    public function decrement($field, int $amount = 1): int
    {
        return $this->dec($field, $amount)->update();
    }
}

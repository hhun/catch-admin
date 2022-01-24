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

namespace catcher\base;

use catcher\CatchQuery;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\RewriteTrait;
use catcher\traits\db\WithTrait;
use think\model\concern\SoftDelete;
use catcher\traits\db\ScopeTrait;
use think\Model;

/**
 *
 * @mixin CatchQuery
 * Class CatchModel
 * @package catcher\base
 */
abstract class CatchModel extends Model
{
    use BaseOptionsTrait;
    use RewriteTrait;
    use ScopeTrait;
    use SoftDelete;
    use WithTrait;

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    protected string $deleteTime = 'deleted_at';

    protected $defaultSoftDelete = 0;

    protected $autoWriteTimestamp = true;

    // 分页 Limit
    public const LIMIT = 10;
    // 开启
    public const ENABLE = 1;
    // 禁用
    public const DISABLE = 2;

    /**
     * 是否有 field
     *
     * @param string $field
     * @return bool
     */
    public function hasField(string $field)
    {
        return property_exists($this, 'field') && in_array($field, $this->field);
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (method_exists($this, 'autoWithRelation')) {
            $this->autoWithRelation();
        }
    }
}

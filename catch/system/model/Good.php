<?php

namespace catchAdmin\system\model;

use think\Model;
use catch\traits\db\BaseOptionsTrait;
use catch\traits\db\ScopeTrait;

/**
 *
 * @property int $id
 * @property string $hi
 * @property int $created_at
 * @property int $updated_at
 * @property int $creator_id
 */
class Good extends Model
{
    use BaseOptionsTrait;
    use ScopeTrait;

    public $name = 'good';

    public $field = [
        'id',

        'hi',

        'created_at',

        'updated_at',

        'creator_id',
    ];
}

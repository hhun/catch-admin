<?php

namespace catchAdmin\system\model;

use think\Model;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
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
    use BaseOptionsTrait, ScopeTrait;
    
    public $name = 'good';
    
    public $field = [
        'id',
        
        'hi',
        
        'created_at',
        
        'updated_at',
        
        'creator_id',
    ];
}
<?php

// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\model;

use catch\base\CatchModel as Model;
use catch\Utils;

class BaseModel extends Model
{
    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->field = $this->getTableFields(Utils::tableWithPrefix($this->name));
    }
}

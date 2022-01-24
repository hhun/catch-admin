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

namespace catchAdmin\permissions\request;

use catchAdmin\permissions\model\Users;
use catcher\base\CatchRequest;

class ProfileRequest extends CatchRequest
{
    protected function rules(): array
    {
        return [
            'username|用户名' => 'require|max:20',
            'email|邮箱' => 'require|email|unique:'.Users::class.',email,'.$this->user()->id,
        ];
    }
}

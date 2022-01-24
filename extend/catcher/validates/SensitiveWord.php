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

namespace catcher\validates;

use catcher\library\Trie;

class SensitiveWord implements ValidateInterface
{
    protected $word;

    public function type(): string
    {
        return 'sensitive_word';
    }

    public function verify($value): bool
    {
        $trie = app(Trie::class);

        if (! $trie->getTries()) {
            return true;
        }

        $word = $trie->getSensitiveWords($trie->getTries(), $value, false);

        return ! $word;
    }

    public function message(): string
    {
        // TODO: Implement message() method.
        return '内容包含敏感词';
    }
}

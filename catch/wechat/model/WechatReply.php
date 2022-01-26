<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\wechat\model;

use catchAdmin\wechat\model\search\ReplySearchTrait;
use catch\base\CatchModel;

class WechatReply extends CatchModel
{
    use ReplySearchTrait;

    protected $name = 'wechat_reply';

    protected $field = [
        'id', //
        'keyword', // 关键字
        'media_id', // 微信资源ID
        'media_url', // 本地资源 URL
        'image_url', // 图片资源
        'title', // 内容
        'content', // 内容
        'type', // 1文字 2图文 3图片 4音乐 5视频 6语音 7转客服
        'status', // 1 正常 2 禁用
        'rule_type', // 1 关键字 2 关注 3 默认
        'creator_id', // 创建人ID
        'created_at', // 创建时间
        'updated_at', // 更新时间
        'deleted_at', // 软删除
    ];

    public const KEYWORD_RULE = 1;
    public const ATTENTION_RULE = 2;
    public const DEFAULT_RULE = 3;


    public const WORD = 1;
    public const GRAPHIC = 2;
    public const IMAGE = 3;
    public const MUSIC = 4;
    public const VIDEO = 5;
    public const VOICE = 6;
    public const CUSTOMER_SERVICE = 7;
}

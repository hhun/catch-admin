<?php

namespace catchAdmin\cms\tables\forms;

use catch\enums\Status;

class SiteLink extends BaseForm
{
    protected ?string $table = 'cms_site_links';

    public function fields(): array
    {
        return [
            self::input('title', '网站标题')->required(),

            self::input('link_to', '跳转地址')->required()->appendValidates([
                self::validateUrl()
            ]),

            self::image('网站图标', 'icon'),

            self::radio('is_show', '展示', Status::Enable)->options(
                self::options()->add('是', Status::Enable)
                    ->add('否', Status::Disable)->render()
            ),

            self::number('weight', '权重')->min(1)->max(10000)
        ];
    }
}

<?php
namespace catchAdmin\cms\tables\forms;

use catcher\enums\Status;
use catcher\library\form\Form;

class Users extends Form
{
    public function fields(): array
    {
        return [
            self::input('username', '用户名')->required()->clearable(true),

            self::image('头像', 'avatar'),

            self::email('email', '邮箱')->required()->clearable(true),

            self::input('password', '密码')->required()->appendValidates([
                self::validatePassword()
            ])->clearable(true),

            self::input('mobile', '手机号')->appendValidates([
                self::validateMobile()
            ])->clearable(true),

            self::radio('status', '状态', Status::Enable)
            ->options(
                self::options()->add('启用', Status::Enable)
                    ->add('禁用', Status::Disable)->render()
            )
        ];
    }
}
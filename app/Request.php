<?php
namespace app;

// 应用请求对象类

use catcher\CatchAuth;
use catcher\enums\Status;
use catcher\exceptions\FailedException;
use catcher\exceptions\LoginFailedException;
use catchAdmin\jwt\exception\TokenBlacklistException;
use catchAdmin\jwt\exception\TokenExpiredException;
use catchAdmin\jwt\exception\TokenInvalidException;
use catcher\enums\Code;

class Request extends \think\Request
{
    /**
     * @var CatchAuth|null
     */
    protected ?CatchAuth $auth = null;

    /**
     * login user
     *
     * @time 2020年01月09日
     * @param null $guard
     * @return mixed
     */
    public function user($guard = null)
    {
        if (!$this->auth) {
            $this->auth = new CatchAuth;
        }

        try {
            $user = $this->auth->guard($guard ? : config('catch.auth.default.guard'))->user();

            if ($user->status == Status::Disable->value) {
                throw new LoginFailedException('该用户已被禁用', Code::USER_FORBIDDEN);
            }
        } catch (TokenExpiredException $e) {
            throw new FailedException(Code::LOGIN_EXPIRED->message(), Code::LOGIN_EXPIRED);
        } catch (TokenBlacklistException $e) {
            throw new FailedException('Token '. Code::LOGIN_BLACKLIST->message(), Code::LOGIN_BLACKLIST);
        } catch (TokenInvalidException $e) {
            throw new FailedException(Code::LOST_LOGIN->message(), Code::LOST_LOGIN);
        } catch (\Exception $e) {
            throw new FailedException('登录用户不合法', Code::LOST_LOGIN);
        }

        return $user;
    }
}

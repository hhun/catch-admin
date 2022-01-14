<?php

namespace catchAdmin\permissions\middleware;

use catcher\exceptions\FailedException;
use catchAdmin\jwt\exception\TokenBlacklistException;
use catchAdmin\jwt\exception\TokenExpiredException;
use catchAdmin\jwt\exception\TokenInvalidException;
use catchAdmin\jwt\facade\JWTAuth;
use think\Middleware;
use catcher\enums\Code;

/**
 * auth token middleware
 */
class AuthTokenMiddleware extends Middleware
{
    public function handle($request, \Closure $next)
    {
        try {
            JWTAuth::auth();
        } catch (TokenExpiredException $e) {
            throw new FailedException(Code::LOGIN_EXPIRED->message(), Code::LOGIN_EXPIRED);
        } catch (TokenBlacklistException $e) {
            throw new FailedException('Token '. Code::LOGIN_BLACKLIST->message(), Code::LOGIN_BLACKLIST);
        } catch (TokenInvalidException $e) {
            throw new FailedException(Code::LOST_LOGIN->message(), Code::LOST_LOGIN);
        } catch (\Exception $e) {
            throw new FailedException('登录用户不合法', Code::LOST_LOGIN);
        }

        return $next($request);
    }
}

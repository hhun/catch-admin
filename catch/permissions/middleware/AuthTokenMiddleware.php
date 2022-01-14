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
            throw new FailedException('token 过期', Code::Login_Expired->value);
        } catch (TokenBlacklistException $e) {
            throw new FailedException('token 被加入黑名单', Code::Login_BlackList->value);
        } catch (TokenInvalidException $e) {
            throw new FailedException('token 不合法', Code::Lost_Login->value);
        } catch (\Exception $e) {
            throw new FailedException('登录用户不合法', Code::Lost_Login->value);
        }

        return $next($request);
    }
}

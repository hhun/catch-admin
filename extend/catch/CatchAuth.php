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

namespace catch;

use catch\enums\Status;
use catch\exceptions\FailedException;
use catch\exceptions\LoginFailedException;
use catchAdmin\jwt\facade\JWTAuth;
use think\facade\Session;
use catchAdmin\jwt\contract\JWTSubject;

class CatchAuth
{
    /**
     * @var mixed
     */
    protected $auth;

    /**
     * @var mixed
     */
    protected $guard;

    // 默认获取
    protected string $username = 'email';

    // 校验字段
    protected string $password = 'password';

    // 保存用户信息
    protected array $user = [];

    /**
     * @var bool
     */
    protected bool $checkPassword = true;

    public function __construct()
    {
        $this->auth = config('catch.auth');

        $this->guard = $this->auth['default']['guard'];
    }

    /**
     * set guard
     *
     * @time 2020年01月07日
     * @param $guard
     * @return $this
     */
    public function guard($guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return mixed
     */
    public function attempt($condition)
    {
        $user = $this->authenticate($condition);

        if (! $user) {
            throw new LoginFailedException();
        }
        if ($user->status == Status::Disable) {
            throw new LoginFailedException('该用户已被禁用|'.$user->username, Code::USER_FORBIDDEN);
        }

        if ($this->checkPassword && ! password_verify($condition['password'], $user->password)) {
            throw new LoginFailedException('登录失败|'.$user->username);
        }

        return $this->{$this->getDriver()}($user);
    }


    /**
     * user
     *
     * @time 2020年09月09日
     * @return mixed
     */
    public function user()
    {
        $user = $this->user[$this->guard] ?? null;

        if (! $user) {
            $driver = $this->getDriver();

            $method = 'getUserFrom' . ucfirst($driver);

            if (! method_exists($this, $method)) {
                throw new FailedException('User not found');
            }

            $user = $this->{$method}();

            $this->user[$this->guard] = $user;
        }

        return $user;
    }

    /**
     * @desc jwt
     *
     * @time 2022年01月19日
     * @return mixed
     */
    protected function getUserFromJWT()
    {
        $model = app($this->getProvider()['model']);

        $this->isUserImplementJWTSubject($model);

        return $model->where($model->getPk(), JWTAuth::auth()[$this->jwtKey()])->find();
    }

    /**
     * @desc session
     *
     * @time 2022年01月19日
     * @return mixed
     */
    protected function getUserFromSession()
    {
        return Session::get($this->sessionUserKey(), null);
    }

    /**
     *
     * @time 2020年01月07日
     * @return bool
     */
    public function logout(): bool
    {
        switch ($this->getDriver()) {
            case 'jwt':
                return true;
            case 'session':
                Session::delete($this->sessionUserKey());
                return true;
            default:
                throw new FailedException('user not found');
        }
    }

    /**
     *
     * @time 2020年01月07日
     * @param $user
     * @return string
     */
    protected function jwt($user): string
    {
        $this->isUserImplementJWTSubject($user);

        $customClaims = array_merge([
            $this->jwtKey() => $user->getJWTIdentifier()
        ], $user->getJWTCustomClaims());

        $token = JWTAuth::builder($customClaims);

        JWTAuth::setToken($token);

        return $token;
    }


    /**
     * @desc is auth model implement JWTSubject
     *
     * @time 2022年01月19日
     * @param $model
     */
    protected function isUserImplementJWTSubject($model)
    {
        if (! $model instanceof JWTSubject) {
            throw new FailedException('If use JWT, Auth Model must implement catchAdmin\jwt\contract\JWTSubject');
        }
    }

    /**
     *
     * @time 2020年01月07日
     * @param $user
     * @return void
     */
    protected function session($user)
    {
        Session::set($this->sessionUserKey(), $user);
    }

    /**
     *
     * @time 2020年01月07日
     * @return string
     */
    protected function sessionUserKey(): string
    {
        return $this->guard.'_user';
    }

    /**
     *
     * @time 2020年01月07日
     * @return string
     */
    protected function jwtKey(): string
    {
        return $this->guard.'_id';
    }

    /**
     *
     * @time 2020年01月07日
     * @return mixed
     */
    protected function getDriver()
    {
        return $this->auth['guards'][$this->guard]['driver'];
    }

    /**
     *
     * @time 2020年01月07日
     * @return mixed
     */
    protected function getProvider()
    {
        if (! isset($this->auth['guards'][$this->guard])) {
            throw new FailedException('Auth Guard Not Found');
        }

        return $this->auth['providers'][$this->auth['guards'][$this->guard]['provider']];
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return mixed
     */
    protected function authenticate($condition)
    {
        $provider = $this->getProvider();

        return $this->{$provider['driver']}($condition);
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return mixed
     */
    protected function orm($condition)
    {
        return app($this->getProvider()['model'])->where($this->filter($condition))->find();
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return array
     */
    protected function filter($condition): array
    {
        $where = [];

        $fields = array_keys(app($this->getProvider()['model'])->getFields());

        foreach ($condition as $field => $value) {
            if (in_array($field, $fields) && $field != $this->password) {
                $where[$field] = $value;
            }
        }

        return $where;
    }

    /**
     *
     * @time 2020年01月07日
     * @param $field
     * @return $this
     */
    public function username($field): self
    {
        $this->username = $field;

        return $this;
    }

    /**
     *
     * @time 2020年01月07日
     * @param $field
     * @return $this
     */
    public function password($field): self
    {
        $this->password = $field;

        return $this;
    }

    /**
     * 忽略密码认证
     *
     * @time 2021年01月27日
     * @return $this
     */
    public function ignorePasswordVerify(): self
    {
        $this->checkPassword = false;

        return $this;
    }
}

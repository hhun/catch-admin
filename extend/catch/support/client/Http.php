<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace catch\support\client;

use GuzzleHttp\Client;

class Http
{
    /**
     * @var ?Client $client
     */
    protected ?Client $client = null;

    /**
     * auth
     *
     * @var array
     */
    protected array $auth = [];

    /**
     * 代理
     *
     * @var array
     */
    protected array $proxy = [];

    /**
     * body
     *
     * @var array
     */
    protected array $body = [];

    /**
     * header
     *
     * @var array
     */
    protected array $header = [];

    /**
     * form params
     *
     * @var array
     */
    protected array $formParams = [];

    /**
     * query set
     *
     * @var array
     */
    protected array $query = [];

    /**
     * json set
     *
     * @var array
     */
    protected array $json = [];

    /**
     *  可选参数
     * @var array
     */
    protected array $options = [];

    /**
     * 异步请求
     *
     * @var bool
     */
    protected bool $async = false;


    /**
     * @var array
     */
    protected array $timeout = [];

    /**
     * @var string
     */
    protected string $token = '';

    protected array $multipart = [];

    /**
     * 忽略证书
     *
     * @var array
     */
    protected array $ignoreSsl = [];

    /**
     * 获取 Guzzle 客户端
     *
     * @time 2020年05月21日
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        if (! $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * headers
     *
     * @time 2020年05月21日
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers): Http
    {
        $this->header = isset($this->header['headers']) ?
                            array_merge($this->header['headers'], $headers) :
                            ['headers' => $headers];

        return $this;
    }

    /**
     * set bearer token
     *
     * @time 2020年05月22日
     * @param string $token
     * @return $this
     */
    public function token(string $token): Http
    {
        $this->header['headers']['authorization'] = 'Bearer '.$token;

        return $this;
    }

    /**
     * body
     *
     * @time 2020年05月21日
     * @param $body
     * @return $this
     */
    public function body($body): Http
    {
        $this->body = [
            'body' => $body
        ];

        return $this;
    }

    /**
     * json
     *
     * @time 2020年05月21日
     * @param array $data
     * @return $this
     */
    public function json(array $data): Http
    {
        $this->json = [
            'json' => $data
        ];

        return $this;
    }

    /**
     * query
     *
     * @time 2020年05月21日
     * @param array $query
     * @return $this
     */
    public function query(array $query): Http
    {
        $this->query = [
            'query' => $query,
        ];

        return $this;
    }

    /**
     * form params
     *
     * @time 2020年05月21日
     * @param array $params
     * @return $this
     */
    public function form(array $params): Http
    {
        $this->formParams = [
            'form_params' => array_merge($this->multipart, $params)
        ];

        return $this;
    }

    /**
     * timeout
     *
     * @time 2020年05月21日
     * @param $timeout
     * @return $this
     */
    public function timeout($timeout): Http
    {
        $this->timeout = [
            'connect_timeout' => $timeout
        ];

        return $this;
    }

    /**
     * 忽略 ssl 证书
     *
     * @return $this
     */
    public function ignoreSsl(): Http
    {
        $this->ignoreSsl = [
            'verify' => false,
        ];

        return $this;
    }

    /**
     * 可选参数
     *
     * @time 2020年05月22日
     * @param array $options
     * @return $this
     */
    public function options(array $options): Http
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Request get
     *
     * @time 2020年05月21日
     * @param string $url
     * @return Response
     */
    public function get(string $url): Response
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }

    /**
     * Request post
     *
     * @time 2020年05月21日
     * @param string $url
     * @return Response
     */
    public function post(string $url): Response
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }

    /**
     * Request put
     *
     * @time 2020年05月21日
     * @param string $url
     * @return Response
     */
    public function put(string $url): Response
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }

    /**
     * Request delete
     *
     * @time 2020年05月21日
     * @param string $url
     * @return Response
     */
    public function delete(string $url): Response
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }


    /**
     * request params merge
     *
     * @time 2020年05月22日
     * @return array
     */
    protected function merge(): array
    {
        return array_merge(
            $this->header,
            $this->query,
            $this->timeout,
            $this->options,
            $this->body,
            $this->auth,
            $this->multipart,
            $this->formParams,
            $this->ignoreSsl
        );
    }

    /**
     * 异步请求
     *
     * @time 2020年05月21日
     * @return $this
     */
    public function async(): Http
    {
        $this->async = true;

        return $this;
    }

    /**
     * 附件
     *
     * @time 2020年05月22日
     * @param string $name
     * @param $resource
     * @param string $filename
     * @return $this
     */
    public function attach(string $name, $resource, string $filename): Http
    {
        $this->multipart = [
            'multipart' => [
                [
                    'name' => $name,
                    'contents' => $resource,
                    'filename' => $filename,
                ]
            ]
        ];

        return $this;
    }

    /**
     * 异步方法
     *
     * @time 2020年05月21日
     * @param $method
     * @return string
     */
    protected function asyncMethod($method): string
    {
        return $this->async ? $method.'Async' : $method;
    }

    /**
     * onHeaders
     *
     * @time 2020年05月22日
     * @param callable $callable
     * @return Http
     */
    public function onHeaders(callable $callable): Http
    {
        $this->options['on_headers'] = $callable;

        return $this;
    }

    /**
     * onStats
     *
     * @time 2020年05月22日
     * @param callable $callable
     * @return Http
     */
    public function onStats(callable $callable): Http
    {
        $this->options['on_stats'] = $callable;

        return $this;
    }

    /**
     * 认证
     *
     * @time 2020年04月30日
     * @param $username
     * @param $password
     * @return $this
     */
    public function auth($username, $password): Http
    {
        $this->options = [
            'auth' => $username, $password
        ];

        return $this;
    }

    /**
     * proxy
     *
     * @time 2020年05月21日
     * @param array $proxy
     * @return $this
     */
    public function proxy(array $proxy): Http
    {
        $this->proxy = $proxy;

        return $this;
    }
}

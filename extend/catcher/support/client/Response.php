<?php

declare(strict_types=1);

namespace catcher\library\client;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;

/**
 * http response
 *
 * From Laravel
 *
 * @time 2020年05月21日
 */
class Response implements \ArrayAccess
{
    /**
     * @var \GuzzleHttp\Psr7\Response|Promise
     */
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }


    /**
     *
     * @time 2020年05月22日
     * @return bool|callable|float|\GuzzleHttp\Psr7\PumpStream|\GuzzleHttp\Psr7\Stream|int|\Iterator|\Psr\Http\Message\StreamInterface|resource|string|null
     */
    public function body()
    {
        return $this->response->getBody();
    }

    /**
     * 响应内容
     *
     * @time 2020年05月22日
     * @return false|string
     */
    public function contents()
    {
        return $this->body()->getContents();
    }

    /**
     *
     * @return array
     */
    public function json(): array
    {
        return \json_decode($this->contents(), true);
    }

    /**
     *
     * @return int
     */
    public function status(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     *
     * @return bool
     */
    public function ok(): bool
    {
        return $this->status() == 200;
    }

    /**
     *
     * @return bool
     */
    public function successful(): bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    /**
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->status() >= 400;
    }

    /**
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * 异步回调
     *
     * @param callable $response
     * @param callable $exception
     * @return FulfilledPromise|Promise|PromiseInterface|RejectedPromise
     */
    public function then(callable $response, callable $exception)
    {
        return $this->response->then($response, $exception);
    }

    /**
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->response->{$name}(...$arguments);
    }

    /**
     *
     * @param mixed $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->json()[$offset]);
    }

    /**
     *
     * @param mixed $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->json()[$offset];
    }

    /**
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
    }

    /**
     * @param mixed $offset
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
    }
}

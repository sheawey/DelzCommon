<?php

namespace Delz\Common\Util;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * http客户端类
 *
 * 封装了GuzzleHttp
 *
 * @package Delz\Common\Util
 */
class Http
{
    /**
     * @var Client
     */
    protected static $httpClient;

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function get($uri, array $options = [])
    {
        return self::getHttpClient()->get($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function post($uri, array $options = [])
    {
        return self::getHttpClient()->post($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function delete($uri, array $options = [])
    {
        return self::getHttpClient()->delete($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function put($uri, array $options = [])
    {
        return self::getHttpClient()->put($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function head($uri, array $options = [])
    {
        return self::getHttpClient()->head($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     */
    public static function patch($uri, array $options = [])
    {
        return self::getHttpClient()->patch($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function getAsync($uri, array $options = [])
    {
        return self::getHttpClient()->getAsync($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function postAsync($uri, array $options = [])
    {
        return self::getHttpClient()->postAsync($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function deleteAsync($uri, array $options = [])
    {
        return self::getHttpClient()->deleteAsync($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function putAsync($uri, array $options = [])
    {
        return self::getHttpClient()->putAsync($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function headAsync($uri, array $options = [])
    {
        return self::getHttpClient()->headAsync($uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return PromiseInterface
     */
    public static function patchAsync($uri, array $options = [])
    {
        return self::getHttpClient()->patchAsync($uri, $options);
    }

    /**
     * 获取httpClient对象
     *
     * @return Client
     */
    public static function getHttpClient()
    {
        if(self::$httpClient) {
            return self::$httpClient;
        }

        $client = new Client();

        return self::$httpClient = $client;
    }

}
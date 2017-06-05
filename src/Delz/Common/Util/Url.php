<?php

namespace Delz\Common\Util;

/**
 * 网址工具类
 *
 * @package Delz\Common\Util
 */
class Url
{
    /**
     * 获取当前请求的Url
     *
     * 注意：命令行下此方法无效
     *
     * @return string
     */
    public static function current()
    {
        if(php_sapi_name() === 'cli') {
            throw new \RuntimeException('function Url::current can not run in the cli.');
        }
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * 标准化网址
     *
     * @param string $url 网址
     * @param array $parameters 网址参数
     * @return string 标准化后的网址
     */
    public static function normalize($url, array $parameters = [])
    {
        $normalizedUrl = $url;
        if (!empty($parameters)) {
            $normalizedUrl .= (false !== strpos($url, '?') ? '&' : '?'). http_build_query($parameters, '', '&');
        }
        return $normalizedUrl;
    }
}
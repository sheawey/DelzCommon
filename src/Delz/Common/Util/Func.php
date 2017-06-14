<?php

namespace Delz\Common\Util;

/**
 * 回调函数工具类
 * @package Delz\Common\Util
 */
class Func
{

    /**
     * 生成闭包函数
     *
     * @param callable $func
     * @param null $args
     * @param callable|null $validator
     * @return \Closure
     */
    public static function closure(callable $func, $args = null, callable $validator = null)
    {
        if(!is_callable($func)) {
            throw new \InvalidArgumentException('Func::closure first args must be callable');
        }

        return function() use ($func, $args, $validator) {
            if(null !== $validator) {
                $validArgs = func_get_args();
                if(!Func::call($validator, $validArgs)){
                    return null;
                }
            }

            return Func::call($func, $args);
        };
    }

    /**
     * 调用回调函数
     *
     * @param callable $func
     * @param null|string|array $args
     * @return mixed
     */
    public static function call(callable $func, $args = null)
    {
        if (!is_callable($func)) {
            throw new \InvalidArgumentException('Func::call first args must be callable');
        }
        if ($args === null) {
            return call_user_func($func);
        }
        if (!is_array($args)) {
            $args = [$args];
        }
        return call_user_func_array($func, $args);
    }
}
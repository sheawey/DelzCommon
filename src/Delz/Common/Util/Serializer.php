<?php

namespace Delz\Common\Util;

/**
 * 序列化工具类
 *
 * 根据系统是否有igbinary，如果有，优先采用igbinary的序列化，否则采用系统默认的
 *
 * @package Delz\Common\Util
 */
class Serializer
{
    /**
     * 序列化
     *
     * @param mixed $data
     * @return mixed
     */
    public static function serialize($data)
    {
        return self::hasIgbinary() ? igbinary_serialize($data) : serialize($data);
    }

    /**
     * 反序列化
     *
     * @param mixed $data
     * @return mixed
     */
    public static function unserialize($data)
    {
        return self::hasIgbinary() ? igbinary_unserialize($data) : unserialize($data);
    }

    /**
     * 是否有php模块igbinary
     *
     * @return bool
     */
    private static function hasIgbinary()
    {
        return extension_loaded('igbinary');
    }
}
<?php

namespace Delz\Common\Util;

/**
 * 字符串工具类
 *
 * @package Delz\Common\Util
 */
class Str
{
    /**
     * 获取指定字符长度
     *
     * @param string $value 要计算长度的字符串
     * @return int 字符长度
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * 首字母大写
     *
     * @param string $value
     * @return string
     */
    public static function ucfirst($value)
    {
        return static::upper(static::subString($value, 0, 1)) . static::subString($value, 1);
    }

    /**
     * 将字符串转化成小写
     *
     * @param string $value 待转化的字符串
     * @return string 转化的字符串
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 将字符串转化成大写
     *
     * @param string $value 待转化的字符串
     * @return string 转化的字符串
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 将字符串转化为数组
     *
     * @param string $value
     * @return array
     */
    public static function toArray($value)
    {
        $arr = [];
        for ($i = 0; $i < self::length($value); $i++) {
            $arr[] = self::subString($value, $i, 1);
        }
        return $arr;
    }

    /**
     * 字符串截取
     *
     * @param string $value 需要截取的字符串
     * @param int $start 开始位置
     * @param int $length 截取长度
     * @param null|string $suffix 截断最后显示字符
     * @return string 截取好的字符串
     */
    public static function subString($value, $start = 0, $length, $suffix = null)
    {
        $value = mb_substr($value, $start, $length, 'UTF-8');
        if ($suffix) {
            $value .= $suffix;
        }
        return $value;
    }

    /**
     * 获取指定长度的随机字符串
     *
     * 字符串类型有：
     *  - alnum    - 字母和数字，默认 0-9A-Za-z
     *  - alpha    - 字母 a-zA-Z
     *  - hexdec   - 十六进制字符 0-9 a-f
     *  - numeric  - 数字, 0-9
     *  - nozero   - 非0数字, 1-9
     *  - distinct - 去除数字和字母混淆的，如0和o，1和I之类的
     *
     * @param int $length 获取的字符串长度
     * @param string $type 字符串类型 alnum()、alpha（字母）、hexdec（十六进制字符）、lowalnum（小写字母和数字）、
     * @return string 指定长度的随机字符串
     */
    public static function random($length = 8, $type = 'alnum')
    {
        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                throw new \InvalidArgumentException('Invalid type.');
                break;
        }
        $pool = str_split($pool, 1);

        $max = count($pool) - 1;

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $pool[mt_rand(0, $max)];
        }

        // 类型选择alnum保证至少有一个字母或者一个数字
        if ($type === 'alnum' AND $length > 1) {
            if (ctype_alpha($str)) {
                // 如果全是字母，加入一个数字
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
            } elseif (ctype_digit($str)) {
                // 如果全是数字，加入一个字母
                $str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
            }
        }

        return $str;
    }

    /**
     * 检测编码是否为utf-8格式
     *
     * @param string $value 被检测的字符串
     * @return bool 检测结果 值: true:是utf8编码格式; false:不是utf8编码格式;
     */
    public static function isUTF8($value)
    {
        return mb_check_encoding($value, 'UTF-8');
    }

    /**
     * 判断是否是中文
     *
     * 字符串编码仅支持UTF-8
     *
     * @param string $value 被检测的字符串
     * @return bool
     */
    public static function isChinese($value)
    {
        $value = (string) $value;
        return !!preg_match('~[\x{4e00}-\x{9fa5}]+~u', $value);
    }
}
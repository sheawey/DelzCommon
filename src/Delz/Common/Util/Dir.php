<?php

namespace Delz\Common\Util;

/**
 * 目录工具类
 *
 * @package Delz\Common\Util
 */
class Dir
{
    /**
     * 新建目录
     *
     * @param string $dir
     * @param int $mode
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function make($dir, $mode = 0755)
    {
        //如果存在相同的文件名，则抛出异常
        if (is_file($dir)) {
            throw new \InvalidArgumentException("Invalid directory name. Some file name is same as it.");
        }

        if (is_dir($dir)) {
            return true;
        }

        mkdir($dir, $mode, true);

        return true;
    }

    /**
     * 查找目录下的文件(支持递归)
     *
     * @param string $dir 要查找的目录
     * @param null|string $includeRegEx 要查找的文件名匹配的正则
     * @param null|string $excludeRegEx 要排除的文件名匹配的正则
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function read($dir, $includeRegEx = null, $excludeRegEx = null)
    {
        $files = [];

        if (!is_dir($dir)) {
            throw new \InvalidArgumentException(
                sprintf('directory "%s" is not exist.', $dir)
            );
        }
        $iterators = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterators as $name => $file) {
            if (!$file->isDir() &&
                ($includeRegEx == null || preg_match($includeRegEx, basename($file))) &&
                ($excludeRegEx == null || !preg_match($excludeRegEx, basename($file)))
            ) {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }


    /**
     * 删除目录
     *
     * @param string $dir
     * @param bool $isIncludeSelf 是否删除本身
     * @return bool
     */
    public static function delete($dir, $isIncludeSelf = false)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $handle = opendir($dir);

        while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..') {
                $realPath = realpath($dir . '/' . $file);
                if (is_dir($realPath)) {
                    static::delete($realPath, true);
                } else {
                    unlink($realPath);
                }
            }
        }

        closedir($handle);

        if ($isIncludeSelf) {
            return rmdir($dir);
        }

        return true;
    }

    /**
     * 标准化目录
     *
     * 举例：
     * c:\\dir1\dir2\dir3//../dir4/./dir5 转化为c:/dir1/dir2/dir4/dir5
     * /usr/local/dir1/dir2/../dir3\dir4/ 转化为/usr/local/dir1/dir3/dir4
     *
     * @param string $path
     * @return string
     */
    public static function normalize($path)
    {
        $path = str_replace('\\', '/', $path);
        $prefix = static::prefix($path);
        $path = substr($path, strlen($prefix));
        $parts = array_filter(explode('/', $path), 'strlen');
        $tokens = array();
        foreach ($parts as $part) {
            switch ($part) {
                case '.':
                    break;
                case '..':
                    if (0 !== count($tokens)) {
                        array_pop($tokens);
                    }
                    break;
                default:
                    $tokens[] = $part;
            }
        }
        return $prefix . implode('/', $tokens);
    }

    /**
     * 获取路径前缀
     *
     * 举例：
     * c://dir1/dir2/dir3 返回  c://
     * ftp://dir1/dir2/dir3 返回 ftp://
     * /usr/local/src 返回 /
     * ./dir1/dir2 返回 空
     * ../dir1/dir2 返回 空
     *
     * @param string $path
     * @return string
     */
    public static function prefix($path)
    {
        preg_match('|^(?P<prefix>([a-zA-Z]+:)?//?)|', $path, $matches);
        if (empty($matches['prefix'])) {
            return '';
        }
        return strtolower($matches['prefix']);
    }

    /**
     * 是否绝对路径
     *
     * @param string $path
     * @return bool
     */
    public static function isAbsolute($path)
    {
        return '' !== static::prefix($path);
    }


}
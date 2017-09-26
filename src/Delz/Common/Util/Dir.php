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
}
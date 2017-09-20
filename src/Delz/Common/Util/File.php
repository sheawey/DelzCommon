<?php

namespace Delz\Common\Util;

/**
 * 文件操作工具类
 *
 * @package Delz\Common\Util
 */
class File
{
    /**
     * 新建目录
     *
     * @param string $dir
     * @param int $mode
     * @return bool
     * @throws \InvalidArgumentException
     */
    public static function mkdir($dir, $mode = 0755)
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
    public static function readDir($dir, $includeRegEx = null, $excludeRegEx = null)
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
     *
     *
     * @param string $dir
     * @param bool $isIncludeSelf 是否删除本身
     * @return bool
     */
    public static function rmdir($dir, $isIncludeSelf = false)
    {
        if (!is_dir($dir)) {
            return false;
        }

        $handle = opendir($dir);

        while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..') {
                $realPath = realpath($dir . '/' . $file);
                if (is_dir($realPath)) {
                    self::rmdir($realPath, true);
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
     * 将内容写入文件
     *
     * @param string $filePath 要写入的文件路径
     * @param string $content 要写入的内容
     * @return int 返回写入到文件内数据的字节数，失败时返回FALSE
     * @throws \InvalidArgumentException
     */
    public static function writeFile($filePath, $content = '')
    {
        //如果存在同样的目录名，则不允许这样的文件名
        if (is_dir($filePath)) {
            throw new \InvalidArgumentException("Invalid file path. Some directory name is same as it.");
        }

        //如果目录不存在，创建目录
        $dir = dirname($filePath);
        self::mkdir($dir);
        return file_put_contents($filePath, $content, LOCK_EX);
    }

    /**
     * 删除文件
     *
     * @param string $filePath
     * @return bool
     */
    public static function rmFile($filePath)
    {
        if(!is_file($filePath)) {
            return true;
        }

        return unlink($filePath);
    }

}
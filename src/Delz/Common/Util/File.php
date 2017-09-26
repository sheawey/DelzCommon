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
     * 将内容写入文件
     *
     * @param string $filePath 要写入的文件路径
     * @param string $content 要写入的内容
     * @return int 返回写入到文件内数据的字节数，失败时返回FALSE
     * @throws \InvalidArgumentException
     */
    public static function write($filePath, $content = '')
    {
        //如果存在同样的目录名，则不允许这样的文件名
        if (is_dir($filePath)) {
            throw new \InvalidArgumentException("Invalid file path. Some directory name is same as it.");
        }

        //如果目录不存在，创建目录
        $dir = dirname($filePath);
        Dir::make($dir);
        return file_put_contents($filePath, $content, LOCK_EX);
    }

    /**
     * 删除文件
     *
     * @param string $filePath
     * @return bool
     */
    public static function delete($filePath)
    {
        if(!is_file($filePath)) {
            return true;
        }

        return unlink($filePath);
    }

}
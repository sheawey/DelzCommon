<?php

namespace Delz\Common\Factory;

/**
 * 工厂接口
 *
 * @package Delz\Common\Factory
 */
interface IFactory
{
    /**
     * 创建一个新的实体对象
     *
     * @return object
     */
    public function createNew();
}
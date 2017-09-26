<?php

namespace Delz\Common\Factory;

/**
 * 工厂类
 *
 * @package Delz\Common\Factory
 */
class Factory implements IFactory
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new $this->className();
    }
}
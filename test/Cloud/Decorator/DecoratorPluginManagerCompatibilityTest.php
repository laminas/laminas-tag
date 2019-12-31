<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */
namespace LaminasTest\ServiceManager;

use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use Laminas\Tag\Cloud\Decorator\DecoratorInterface;
use Laminas\Tag\Cloud\DecoratorPluginManager;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Example test of using CommonPluginManagerTrait
 */
class DecoratorPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager()
    {
        return new DecoratorPluginManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException()
    {
        return \RuntimeException::class;
    }

    protected function getInstanceOf()
    {
        return DecoratorInterface::class;
    }
}

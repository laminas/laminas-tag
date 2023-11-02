<?php

declare(strict_types=1);

namespace LaminasTest\Tag\Cloud\Decorator;

use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use Laminas\Tag\Cloud\Decorator\DecoratorInterface;
use Laminas\Tag\Cloud\DecoratorPluginManager;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Example test of using CommonPluginManagerTrait
 */
class DecoratorPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    /** @return DecoratorPluginManager */
    protected static function getPluginManager()
    {
        return new DecoratorPluginManager(new ServiceManager());
    }

    /** @return string */
    protected function getV2InvalidPluginException()
    {
        return RuntimeException::class;
    }

    /** @return string */
    protected function getInstanceOf()
    {
        return DecoratorInterface::class;
    }
}

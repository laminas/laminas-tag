<?php

namespace Laminas\Tag\Cloud;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\InvokableFactory;
use RuntimeException;

/**
 * Plugin manager implementation for decorators.
 *
 * Enforces that decorators retrieved are instances of
 * Decorator\DecoratorInterface. Additionally, it registers a number of default
 * decorators available.
 */
class DecoratorPluginManager extends AbstractPluginManager
{
    protected $aliases = [
        'htmlcloud' => Decorator\HtmlCloud::class,
        'htmlCloud' => Decorator\HtmlCloud::class,
        'Htmlcloud' => Decorator\HtmlCloud::class,
        'HtmlCloud' => Decorator\HtmlCloud::class,
        'htmltag'   => Decorator\HtmlTag::class,
        'htmlTag'   => Decorator\HtmlTag::class,
        'Htmltag'   => Decorator\HtmlTag::class,
        'HtmlTag'   => Decorator\HtmlTag::class,
        'tag'       => Decorator\HtmlTag::class,
        'Tag'       => Decorator\HtmlTag::class,

        // Legacy Zend Framework aliases
        \Zend\Tag\Cloud\Decorator\HtmlCloud::class => Decorator\HtmlCloud::class,
        \Zend\Tag\Cloud\Decorator\HtmlTag::class => Decorator\HtmlTag::class,

        // v2 normalized FQCNs
        'zendtagclouddecoratorhtmlcloud' => Decorator\HtmlCloud::class,
        'zendtagclouddecoratorhtmltag' => Decorator\HtmlTag::class,
    ];

    protected $factories = [
        Decorator\HtmlCloud::class => InvokableFactory::class,
        Decorator\HtmlTag::class   => InvokableFactory::class,
        // Legacy (v2) due to alias resolution; canonical form of resolved
        // alias is used to look up the factory, while the non-normalized
        // resolved alias is used as the requested name passed to the factory.
        'laminastagclouddecoratorhtmlcloud' => InvokableFactory::class,
        'laminastagclouddecoratorhtmltag'   => InvokableFactory::class
    ];

    protected $instanceOf = Decorator\DecoratorInterface::class;

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Validates against `$instanceOf`.
     *
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if (! $instance instanceof $this->instanceOf) {
            throw new InvalidServiceException(sprintf(
                '%s can only create instances of %s; %s is invalid',
                get_class($this),
                $this->instanceOf,
                (is_object($instance) ? get_class($instance) : gettype($instance))
            ));
        }
    }

    /**
     * Validate the plugin is of the expected type (v2).
     *
     * Proxies to `validate()`.
     *
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validatePlugin($instance)
    {
        try {
            $this->validate($instance);
        } catch (InvalidServiceException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

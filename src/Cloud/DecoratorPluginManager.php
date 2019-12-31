<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Tag\Cloud;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\Tag\Exception;

/**
 * Plugin manager implementation for decorators.
 *
 * Enforces that decorators retrieved are instances of
 * Decorator\DecoratorInterface. Additionally, it registers a number of default
 * decorators available.
 *
 * @category   Laminas
 * @package    Laminas_Tag
 * @subpackage Cloud
 */
class DecoratorPluginManager extends AbstractPluginManager
{
    /**
     * Default set of decorators
     *
     * @var array
     */
    protected $invokableClasses = array(
        'htmlcloud' => 'Laminas\Tag\Cloud\Decorator\HtmlCloud',
        'htmltag'   => 'Laminas\Tag\Cloud\Decorator\HtmlTag',
        'tag'       => 'Laminas\Tag\Cloud\Decorator\HtmlTag',
   );

    /**
     * Validate the plugin
     *
     * Checks that the decorator loaded is an instance
     * of Decorator\DecoratorInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\InvalidArgumentException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Decorator\DecoratorInterface) {
            // we're okay
            return;
        }

        throw new Exception\InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Decorator\DecoratorInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}

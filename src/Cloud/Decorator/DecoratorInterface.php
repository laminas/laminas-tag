<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Tag\Cloud\Decorator;

/**
 * Interface for decorators
 */
interface DecoratorInterface
{
    /**
     * Constructor
     *
     * Allow passing options to the constructor.
     *
     * @param  mixed $options
     */
    public function __construct($options = null);

    /**
     * Render a list of tags
     *
     * @param  mixed $tags
     * @return string
     */
    public function render($tags);
}

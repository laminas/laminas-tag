<?php

declare(strict_types=1);

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

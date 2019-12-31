<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Tag\Cloud\Decorator;

use Laminas\Stdlib\ArrayUtils;
use Laminas\Tag\Cloud\Decorator\DecoratorInterface as Decorator;
use Traversable;

/**
 * Abstract class for tag decorators
 *
 * @category  Laminas
 * @package   Laminas_Tag
 */
abstract class AbstractTag implements Decorator
{
    /**
     * Option keys to skip when calling setOptions()
     *
     * @var array
     */
    protected $skipOptions = array(
        'options',
        'config',
    );

    /**
     * Create a new cloud decorator with options
     *
     * @param  array|Traversable $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options from array
     *
     * @param  array $options Configuration for the decorator
     * @return AbstractTag
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (in_array(strtolower($key), $this->skipOptions)) {
                continue;
            }

            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }
}

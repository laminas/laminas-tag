<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Tag\Cloud\TestAsset;

class CloudDummy2 extends \Laminas\Tag\Cloud\Decorator\HtmlCloud
{
    // @codingStandardsIgnoreStart
    protected $_foo;
    // @codingStandardsIgnoreEnd

    public function setFoo($value)
    {
        $this->_foo = $value;
    }

    public function getFoo()
    {
        return $this->_foo;
    }
}

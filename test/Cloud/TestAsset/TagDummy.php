<?php

namespace LaminasTest\Tag\Cloud\TestAsset;

class TagDummy extends \Laminas\Tag\Cloud\Decorator\HtmlTag
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

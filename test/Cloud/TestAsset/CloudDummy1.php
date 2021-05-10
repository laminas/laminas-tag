<?php

namespace LaminasTest\Tag\Cloud\TestAsset;

class CloudDummy1 extends \Laminas\Tag\Cloud\Decorator\HtmlCloud
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

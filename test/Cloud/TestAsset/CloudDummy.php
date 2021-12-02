<?php

declare(strict_types=1);

namespace LaminasTest\Tag\Cloud\TestAsset;

use Laminas\Tag\Cloud\Decorator\HtmlCloud;

class CloudDummy extends HtmlCloud
{
    // phpcs:ignore
    protected $_foo;

    /** @param mixed $value */
    public function setFoo($value)
    {
        $this->_foo = $value;
    }

    /** @return mixed */
    public function getFoo()
    {
        return $this->_foo;
    }
}

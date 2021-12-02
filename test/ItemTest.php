<?php

declare(strict_types=1);

namespace LaminasTest\Tag;

use ArrayObject;
use Laminas\Tag;
use Laminas\Tag\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testConstructor()
    {
        $tag = new Tag\Item([
            'title'  => 'foo',
            'weight' => 10,
            'params' => [
                'bar' => 'baz',
            ],
        ]);

        $this->assertEquals('foo', $tag->getTitle());
        $this->assertEquals(10, $tag->getWeight());
        $this->assertEquals('baz', $tag->getParam('bar'));
    }

    public function testSetOptions()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1]);
        $tag->setOptions([
            'title'  => 'bar',
            'weight' => 10,
            'params' => [
                'bar' => 'baz',
            ],
        ]);

        $this->assertEquals('bar', $tag->getTitle());
        $this->assertEquals(10, $tag->getWeight());
        $this->assertEquals('baz', $tag->getParam('bar'));
    }

    public function testSetParam()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1]);
        $tag->setParam('bar', 'baz');

        $this->assertEquals('baz', $tag->getParam('bar'));
    }

    public function testSetTitle()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1]);
        $tag->setTitle('baz');

        $this->assertEquals('baz', $tag->getTitle());
    }

    public function testInvalidTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title must be a string');
        $tag = new Tag\Item(['title' => 10, 'weight' => 1]);
    }

    public function testSetWeight()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1]);
        $tag->setWeight('10');

        $this->assertEquals(10.0, $tag->getWeight());
        $this->assertIsFloat($tag->getWeight());
    }

    public function testInvalidWeight()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight must be numeric');
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 'foobar']);
    }

    public function testSkipOptions()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1, 'param' => 'foobar']);
        // In case would fail due to an error
    }

    public function testInvalidOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid options provided to constructor');
        $tag = new Tag\Item('test');
    }

    public function testMissingTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Title was not set');
        $tag = new Tag\Item(['weight' => 1]);
    }

    public function testMissingWeight()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight was not set');
        $tag = new Tag\Item(['title' => 'foo']);
    }

    /**
     * This test uses ArrayObject, which will have essentially the
     * same behavior as Laminas\Config\Config; the code is looking only
     * for a Traversable.
     */
    public function testConfigOptions()
    {
        $tag = new Tag\Item(new ArrayObject(['title' => 'foo', 'weight' => 1]));

        $this->assertEquals($tag->getTitle(), 'foo');
        $this->assertEquals($tag->getWeight(), 1);
    }

    public function testGetNonSetParam()
    {
        $tag = new Tag\Item(['title' => 'foo', 'weight' => 1]);

        $this->assertNull($tag->getParam('foo'));
    }
}

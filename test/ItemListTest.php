<?php

declare(strict_types=1);

namespace LaminasTest\Tag;

use Laminas\Tag;
use Laminas\Tag\Exception\InvalidArgumentException;
use Laminas\Tag\Exception\OutOfBoundsException;
use PHPUnit\Framework\TestCase;

use function count;

class ItemListTest extends TestCase
{
    public function testArrayAccessAndCount()
    {
        $list = new Tag\ItemList();

        $list[] = $this->_getItem('foo');
        $list[] = $this->_getItem('bar');
        $list[] = $this->_getItem('baz');
        $this->assertEquals(count($list), 3);

        unset($list[2]);
        $this->assertEquals(count($list), 2);

        $list[5] = $this->_getItem('bat');
        $this->assertTrue(isset($list[5]));

        $this->assertEquals($list[1]->getTitle(), 'bar');
    }

    public function testSeekableIterator()
    {
        $list = new Tag\ItemList();

        $values = ['foo', 'bar', 'baz'];
        foreach ($values as $value) {
            $list[] = $this->_getItem($value);
        }

        foreach ($list as $key => $item) {
            $this->assertEquals($item->getTitle(), $values[$key]);
        }

        $list->seek(2);
        $this->assertEquals($list->current()->getTitle(), $values[2]);
    }

    public function testSeektableIteratorThrowsBoundsException()
    {
        $list = new Tag\ItemList();

        $values = ['foo', 'bar', 'baz'];
        foreach ($values as $value) {
            $list[] = $this->_getItem($value);
        }
        $list->seek(2);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Invalid seek position');
        $list->seek(3);
    }

    public function testInvalidItem()
    {
        $list = new Tag\ItemList();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Item must implement Laminas\Tag\TaggableInterface');
        $list[] = 'test';
    }

    public function testSpreadWeightValues()
    {
        $list = new Tag\ItemList();

        $list[] = $this->_getItem('foo', 1);
        $list[] = $this->_getItem('bar', 5);
        $list[] = $this->_getItem('baz', 50);

        $list->spreadWeightValues([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $weightValues = [];
        foreach ($list as $item) {
            $weightValues[] = $item->getParam('weightValue');
        }

        $expectedWeightValues = [1, 2, 10];

        $this->assertEquals($weightValues, $expectedWeightValues);
    }

    public function testSpreadWeightValuesWithSingleValue()
    {
        $list = new Tag\ItemList();

        $list[] = $this->_getItem('foo', 1);
        $list[] = $this->_getItem('bar', 5);
        $list[] = $this->_getItem('baz', 50);

        $list->spreadWeightValues(['foobar']);

        $weightValues = [];
        foreach ($list as $item) {
            $weightValues[] = $item->getParam('weightValue');
        }

        $expectedWeightValues = ['foobar', 'foobar', 'foobar'];

        $this->assertEquals($weightValues, $expectedWeightValues);
    }

    public function testSpreadWeightValuesWithEmptyValuesArray()
    {
        $list = new Tag\ItemList();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value list may not be empty');
        $list->spreadWeightValues([]);
    }

    // @codingStandardsIgnoreStart
    protected function _getItem($title = 'foo', $weight = 1)
    {
        // @codingStandardsIgnoreEnd
        return new Tag\Item(['title' => $title, 'weight' => $weight]);
    }
}

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
    public function testArrayAccessAndCount(): void
    {
        $list = new Tag\ItemList();

        $list[] = $this->generateTagItem('foo');
        $list[] = $this->generateTagItem('bar');
        $list[] = $this->generateTagItem('baz');
        $this->assertEquals(count($list), 3);

        unset($list[2]);
        $this->assertEquals(count($list), 2);

        $list[5] = $this->generateTagItem('bat');
        $this->assertTrue(isset($list[5]));

        $this->assertEquals($list[1]->getTitle(), 'bar');
    }

    public function testSeekableIterator(): void
    {
        $list = new Tag\ItemList();

        $values = ['foo', 'bar', 'baz'];
        foreach ($values as $value) {
            $list[] = $this->generateTagItem($value);
        }

        foreach ($list as $key => $item) {
            $this->assertEquals($item->getTitle(), $values[$key]);
        }

        $list->seek(2);
        $this->assertEquals($list->current()->getTitle(), $values[2]);
    }

    public function testSeektableIteratorThrowsBoundsException(): void
    {
        $list = new Tag\ItemList();

        $values = ['foo', 'bar', 'baz'];
        foreach ($values as $value) {
            $list[] = $this->generateTagItem($value);
        }
        $list->seek(2);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Invalid seek position');
        $list->seek(3);
    }

    public function testInvalidItem(): void
    {
        $list = new Tag\ItemList();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Item must implement Laminas\Tag\TaggableInterface');
        /** @psalm-suppress InvalidArgument */
        $list[] = 'test';
    }

    public function testSpreadWeightValues(): void
    {
        $list = new Tag\ItemList();

        $list[] = $this->generateTagItem('foo', 1);
        $list[] = $this->generateTagItem('bar', 5);
        $list[] = $this->generateTagItem('baz', 50);

        $list->spreadWeightValues([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $weightValues = [];
        foreach ($list as $item) {
            $weightValues[] = $item->getParam('weightValue');
        }

        $expectedWeightValues = [1, 2, 10];

        $this->assertEquals($weightValues, $expectedWeightValues);
    }

    public function testSpreadWeightValuesWithSingleValue(): void
    {
        $list = new Tag\ItemList();

        $list[] = $this->generateTagItem('foo', 1);
        $list[] = $this->generateTagItem('bar', 5);
        $list[] = $this->generateTagItem('baz', 50);

        $list->spreadWeightValues(['foobar']);

        $weightValues = [];
        foreach ($list as $item) {
            $weightValues[] = $item->getParam('weightValue');
        }

        $expectedWeightValues = ['foobar', 'foobar', 'foobar'];

        $this->assertEquals($weightValues, $expectedWeightValues);
    }

    public function testSpreadWeightValuesWithEmptyValuesArray(): void
    {
        $list = new Tag\ItemList();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value list may not be empty');
        $list->spreadWeightValues([]);
    }

    /** @param numeric $weight */
    private function generateTagItem(string $title = 'foo', int|float|string $weight = 1): Tag\Item
    {
        return new Tag\Item(['title' => $title, 'weight' => $weight]);
    }
}

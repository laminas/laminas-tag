<?php

namespace LaminasTest\Tag\Cloud\Decorator;

use ArrayObject;
use Laminas\Tag;
use Laminas\Tag\Cloud\Decorator;
use PHPUnit\Framework\TestCase;

class HtmlTagTest extends TestCase
{
    public function testDefaultOutput()
    {
        $decorator = new Decorator\HtmlTag();
        $expected  = ['<li><a href="http://first" style="font-size: 10px;">foo</a></li>',
                           '<li><a href="http://second" style="font-size: 13px;">bar</a></li>',
                           '<li><a href="http://third" style="font-size: 20px;">baz</a></li>'];

        $this->assertEquals($decorator->render($this->_getTagList()), $expected);
    }

    public function testNestedTags()
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setHtmlTags(['span' => ['class' => 'tag'], 'li']);
        $expected  = ['<li><span class="tag"><a href="http://first" style="font-size: 10px;">foo</a></span></li>',
                           '<li><span class="tag"><a href="http://second" style="font-size: 13px;">bar</a></span></li>',
                           '<li><span class="tag"><a href="http://third" style="font-size: 20px;">baz</a></span></li>'];

        $this->assertEquals($decorator->render($this->_getTagList()), $expected);
    }

    public function testFontSizeSpread()
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setFontSizeUnit('pt')
                  ->setMinFontSize(5)
                  ->setMaxFontSize(50);

        $expected  = ['<li><a href="http://first" style="font-size: 5pt;">foo</a></li>',
                           '<li><a href="http://second" style="font-size: 15pt;">bar</a></li>',
                           '<li><a href="http://third" style="font-size: 50pt;">baz</a></li>'];

        $this->assertEquals($decorator->render($this->_getTagList()), $expected);
    }

    public function testClassListSpread()
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setClassList(['small', 'medium', 'large']);

        $expected  = ['<li><a href="http://first" class="small">foo</a></li>',
                           '<li><a href="http://second" class="medium">bar</a></li>',
                           '<li><a href="http://third" class="large">baz</a></li>'];

        $this->assertEquals($decorator->render($this->_getTagList()), $expected);
    }

    public function testEmptyClassList()
    {
        $decorator = new Decorator\HtmlTag();

        $this->expectException('Laminas\Tag\Cloud\Decorator\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Classlist is empty');
        $decorator->setClassList([]);
    }

    public function testInvalidClassList()
    {
        $decorator = new Decorator\HtmlTag();

        $this->expectException('Laminas\Tag\Cloud\Decorator\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Classlist contains an invalid classname');
        $decorator->setClassList([[]]);
    }

    public function testInvalidFontSizeUnit()
    {
        $decorator = new Decorator\HtmlTag();

        $this->expectException('Laminas\Tag\Cloud\Decorator\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Invalid fontsize unit specified');
        $decorator->setFontSizeUnit('foo');
    }

    public function testInvalidMinFontSize()
    {
        $decorator = new Decorator\HtmlTag();

        $this->expectException('Laminas\Tag\Cloud\Decorator\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Fontsize must be numeric');
        $decorator->setMinFontSize('foo');
    }

    public function testInvalidMaxFontSize()
    {
        $decorator = new Decorator\HtmlTag();

        $this->expectException('Laminas\Tag\Cloud\Decorator\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('Fontsize must be numeric');
        $decorator->setMaxFontSize('foo');
    }

    public function testConstructorWithArray()
    {
        $decorator = new Decorator\HtmlTag(['minFontSize' => 5, 'maxFontSize' => 10, 'fontSizeUnit' => 'pt']);

        $this->assertEquals(5, $decorator->getMinFontSize());
        $this->assertEquals(10, $decorator->getMaxFontSize());
        $this->assertEquals('pt', $decorator->getFontSizeUnit());
    }

    /**
     * This test uses ArrayObject, which will have essentially the
     * same behavior as Laminas\Config\Config; the code is looking only
     * for a Traversable.
     */
    public function testConstructorWithConfig()
    {
        $decorator = new Decorator\HtmlTag(new ArrayObject([
            'minFontSize' => 5,
            'maxFontSize' => 10,
            'fontSizeUnit' => 'pt',
        ]));

        $this->assertEquals(5, $decorator->getMinFontSize());
        $this->assertEquals(10, $decorator->getMaxFontSize());
        $this->assertEquals('pt', $decorator->getFontSizeUnit());
    }

    public function testSetOptions()
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setOptions(['minFontSize' => 5, 'maxFontSize' => 10, 'fontSizeUnit' => 'pt']);

        $this->assertEquals(5, $decorator->getMinFontSize());
        $this->assertEquals(10, $decorator->getMaxFontSize());
        $this->assertEquals('pt', $decorator->getFontSizeUnit());
    }

    public function testSkipOptions()
    {
        $decorator = new Decorator\HtmlTag(['options' => 'foobar']);
        // In case would fail due to an error
    }

    // @codingStandardsIgnoreStart
    protected function _getTagList()
    {
        // @codingStandardsIgnoreEnd
        $list   = new Tag\ItemList();
        $list[] = new Tag\Item(['title' => 'foo', 'weight' => 1, 'params' => ['url' => 'http://first']]);
        $list[] = new Tag\Item(['title' => 'bar', 'weight' => 3, 'params' => ['url' => 'http://second']]);
        $list[] = new Tag\Item(['title' => 'baz', 'weight' => 10, 'params' => ['url' => 'http://third']]);

        return $list;
    }

    public function getTags()
    {
        $tags = new Tag\ItemList();
        $tags[] = new Tag\Item([
            'title' => 'tag',
            'weight' => 1,
            'params' => [
                'url' => 'http://testing',
            ],
        ]);
        return $tags;
    }

    public function invalidHtmlElementProvider()
    {
        return [
            [['_foo']],
            [['&foo']],
            [[' foo']],
            [[' foo']],
            [[
                '_foo' => [],
            ]],
        ];
    }

    /**
     * @dataProvider invalidHtmlElementProvider
     */
    public function testInvalidElementNamesRaiseAnException($tags)
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setHTMLTags($tags);
        $this->expectException('Laminas\Tag\Exception\InvalidElementNameException');
        $decorator->render($this->getTags());
    }

    public function invalidAttributeProvider()
    {
        return [
            [[
                'foo' => [
                    '&bar' => 'baz',
                ],
            ]],
            [[
                'foo' => [
                    ':bar&baz' => 'bat',
                ],
            ]],
            [[
                'foo' => [
                    'bar/baz' => 'bat',
                ],
            ]],
        ];
    }

    /**
     * @dataProvider invalidAttributeProvider
     */
    public function testInvalidAttributesRaiseAnException($tags)
    {
        $decorator = new Decorator\HtmlTag();
        $decorator->setHTMLTags($tags);
        $this->expectException('Laminas\Tag\Exception\InvalidAttributeNameException');
        $decorator->render($this->getTags());
    }
}

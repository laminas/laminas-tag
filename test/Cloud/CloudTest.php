<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Tag\Cloud;

use Laminas\Tag;
use Laminas\Tag\Cloud;
use Laminas\Tag\Cloud\DecoratorPluginManager;
use Laminas\Tag\Exception\InvalidArgumentException;
use stdClass;

/**
 * @group      Laminas_Tag
 * @group      Laminas_Tag_Cloud
 */
class CloudTest extends \PHPUnit_Framework_TestCase
{

    public function testGetAndSetItemList()
    {
        $cloud = $this->_getCloud();
        $this->assertInstanceOf('Laminas\Tag\ItemList', $cloud->getItemList());

        $cloud->setItemList(new ItemListDummy);
        $this->assertInstanceOf('LaminasTest\Tag\Cloud\ItemListDummy', $cloud->getItemList());
    }

    public function testSetCloudDecoratorViaArray()
    {
        $cloud = $this->_getCloud();

        $cloud->setCloudDecorator(array(
            'decorator' => 'CloudDummy',
            'options'   => array('foo' => 'bar'),
        ));
        $this->assertInstanceOf('LaminasTest\Tag\Cloud\TestAsset\CloudDummy', $cloud->getCloudDecorator());
        $this->assertEquals('bar', $cloud->getCloudDecorator()->getFoo());
    }

    public function testGetAndSetCloudDecorator()
    {
        $cloud = $this->_getCloud();
        $this->assertInstanceOf('Laminas\Tag\Cloud\Decorator\HtmlCloud', $cloud->getCloudDecorator());

        $cloud->setCloudDecorator(new TestAsset\CloudDummy());
        $this->assertInstanceOf('LaminasTest\Tag\Cloud\TestAsset\CloudDummy', $cloud->getCloudDecorator());
    }

    public function testSetInvalidCloudDecorator()
    {
        $cloud = $this->_getCloud();

        $this->setExpectedException('Laminas\Tag\Exception\InvalidArgumentException', 'DecoratorInterface');
        $cloud->setCloudDecorator(new stdClass());
    }

    public function testSetTagDecoratorViaArray()
    {
        $cloud = $this->_getCloud();

        $cloud->setTagDecorator(array(
            'decorator' => 'TagDummy',
            'options'   => array('foo' => 'bar'),
        ));
        $this->assertInstanceOf('LaminasTest\Tag\Cloud\TestAsset\TagDummy', $cloud->getTagDecorator());
        $this->assertEquals('bar', $cloud->getTagDecorator()->getFoo());
    }

    public function testGetAndSetTagDecorator()
    {
        $cloud = $this->_getCloud();
        $this->assertInstanceOf('Laminas\Tag\Cloud\Decorator\HtmlTag', $cloud->getTagDecorator());

        $cloud->setTagDecorator(new TestAsset\TagDummy());
        $this->assertInstanceOf('LaminasTest\Tag\Cloud\TestAsset\TagDummy', $cloud->getTagDecorator());
    }

    public function testSetInvalidTagDecorator()
    {
        $cloud = $this->_getCloud();

        $this->setExpectedException('Laminas\Tag\Exception\InvalidArgumentException', 'DecoratorInterface');
        $cloud->setTagDecorator(new stdClass());
    }

    public function testSetDecoratorPluginManager()
    {
        $decorators = new DecoratorPluginManager();
        $cloud      = $this->_getCloud(array(), null);
        $cloud->setDecoratorPluginManager($decorators);
        $this->assertSame($decorators, $cloud->getDecoratorPluginManager());
    }

    public function testSetDecoratorPluginManagerViaOptions()
    {
        $decorators = new DecoratorPluginManager();
        $cloud      = $this->_getCloud(
            array('decoratorPluginManager' => $decorators),
            null
        );
        $this->assertSame($decorators, $cloud->getDecoratorPluginManager());
    }

    public function testAppendTagAsArray()
    {
        $cloud = $this->_getCloud();
        $list  = $cloud->getItemList();

        $cloud->appendTag(array(
            'title'  => 'foo',
            'weight' => 1,
        ));

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testAppendTagAsItem()
    {
        $cloud = $this->_getCloud();
        $list  = $cloud->getItemList();

        $cloud->appendTag(new Tag\Item(array(
            'title'  => 'foo',
            'weight' => 1,
        )));

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testAppendInvalidTag()
    {
        $cloud = $this->_getCloud();

        $this->setExpectedException('Laminas\Tag\Exception\InvalidArgumentException', 'TaggableInterface');
        $cloud->appendTag('foo');
    }

    public function testSetTagsAsArray()
    {
        $cloud = $this->_getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags(array(
            array(
                'title'  => 'foo',
                'weight' => 1,
            ),
            array(
                'title'  => 'bar',
                'weight' => 2,
            )
        ));

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetTagsAsItem()
    {
        $cloud = $this->_getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags(array(
            new Tag\Item( array(
                'title'  => 'foo',
                'weight' => 1,
            )),
            new Tag\Item( array(
                'title'  => 'bar',
                'weight' => 2,
            )),
        ));

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetTagsMixed()
    {
        $cloud = $this->_getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags(array(
            array(
                'title'  => 'foo',
                'weight' => 1,
            ),
            new Tag\Item(array(
                'title'  => 'bar',
                'weight' => 2,
            )),
        ));

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetInvalidTags()
    {
        $cloud = $this->_getCloud();

        $this->setExpectedException('Laminas\Tag\Exception\InvalidArgumentException', 'TaggableInterface');
        $cloud->setTags(array('foo'));
    }

    public function testConstructorWithArray()
    {
        $cloud = $this->_getCloud(array(
            'tags' => array(
                array(
                    'title'  => 'foo',
                    'weight' => 1,
                ),
            ),
        ));
        $list  = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testConstructorWithConfig()
    {
        $cloud = $this->_getCloud( new \Laminas\Config\Config(array(
            'tags' => array(
                array(
                    'title'  => 'foo',
                    'weight' => 1,
                ),
            ),
        )));
        $list  = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testSetOptions()
    {
        $cloud = $this->_getCloud();
        $cloud->setOptions(array(
            'tags' => array(
                array(
                    'title'  => 'foo',
                    'weight' => 1,
                ),
            ),
        ));
        $list = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testSkipOptions()
    {
        $cloud = $this->_getCloud(array('options' => 'foobar'));
        // In case would fail due to an error
    }

    public function testRender()
    {
        $cloud = $this->_getCloud(array(
            'tags' => array(
                array(
                    'title'  => 'foo',
                    'weight' => 1,
                ),
                array(
                    'title'  => 'bar',
                    'weight' => 3,
                ),
            ),
        ));
        $expected = '<ul class="laminas-tag-cloud">'
            . '<li><a href="" style="font-size: 10px;">foo</a></li> '
            . '<li><a href="" style="font-size: 20px;">bar</a></li>'
            . '</ul>';
        $this->assertEquals($expected, $cloud->render());
    }

    public function testRenderEmptyCloud()
    {
        $cloud = $this->_getCloud();
        $this->assertEquals('', $cloud->render());
    }

    public function testRenderViaToString()
    {
        $cloud    = $this->_getCloud(array(
            'tags' => array(
                array(
                    'title'  => 'foo',
                    'weight' => 1,
                ),
                array(
                    'title'  => 'bar',
                    'weight' => 3,
                ),
            ),
        ));
        $expected = '<ul class="laminas-tag-cloud">'
            . '<li><a href="" style="font-size: 10px;">foo</a></li> '
            . '<li><a href="" style="font-size: 20px;">bar</a></li>'
            . '</ul>';
        $this->assertEquals($expected, (string)$cloud);
    }

    protected function _getCloud(
        $options = null,
        $setDecoratorPluginManager = true
    ) {
        $cloud = new Tag\Cloud($options);

        if ($setDecoratorPluginManager) {
            $decorators = $cloud->getDecoratorPluginManager();
            $decorators->setInvokableClass(
                'clouddummy', 'LaminasTest\Tag\Cloud\TestAsset\CloudDummy'
            );
            $decorators->setInvokableClass(
                'clouddummy1', 'LaminasTest\Tag\Cloud\TestAsset\CloudDummy1'
            );
            $decorators->setInvokableClass(
                'clouddummy2', 'LaminasTest\Tag\Cloud\TestAsset\CloudDummy2'
            );
            $decorators->setInvokableClass(
                'tagdummy', 'LaminasTest\Tag\Cloud\TestAsset\TagDummy'
            );
        }

        return $cloud;
    }
}

class ItemListDummy extends Tag\ItemList
{
}

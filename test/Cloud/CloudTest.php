<?php

namespace LaminasTest\Tag\Cloud;

use ArrayObject;
use Laminas\ServiceManager\Config as SMConfig;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Tag;
use Laminas\Tag\Cloud\Decorator\HtmlCloud;
use Laminas\Tag\Cloud\Decorator\HtmlTag;
use Laminas\Tag\Cloud\DecoratorPluginManager;
use Laminas\Tag\ItemList;
use LaminasTest\Tag\Cloud\TestAsset\CloudDummy;
use LaminasTest\Tag\Cloud\TestAsset\TagDummy;
use PHPUnit\Framework\TestCase;
use stdClass;

class CloudTest extends TestCase
{
    public function testGetAndSetItemList()
    {
        $cloud = $this->getCloud();
        $this->assertInstanceOf(ItemList::class, $cloud->getItemList());

        $cloud->setItemList(new TestAsset\ItemListDummy());
        $this->assertInstanceOf(TestAsset\ItemListDummy::class, $cloud->getItemList());
    }

    public function testSetCloudDecoratorViaArray()
    {
        $cloud = $this->getCloud();
        $cloud->setCloudDecorator([
            'decorator' => 'CloudDummy',
            'options'   => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf(CloudDummy::class, $cloud->getCloudDecorator());
        $this->assertEquals('bar', $cloud->getCloudDecorator()->getFoo());
    }

    public function testGetAndSetCloudDecorator()
    {
        $cloud = $this->getCloud();
        $this->assertInstanceOf(HtmlCloud::class, $cloud->getCloudDecorator());

        $cloud->setCloudDecorator(new TestAsset\CloudDummy());
        $this->assertInstanceOf(CloudDummy::class, $cloud->getCloudDecorator());
    }

    public function testSetInvalidCloudDecorator()
    {
        $cloud = $this->getCloud();

        $this->expectException('Laminas\Tag\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('DecoratorInterface');
        $cloud->setCloudDecorator(new stdClass());
    }

    public function testSetTagDecoratorViaArray()
    {
        $cloud = $this->getCloud();
        $cloud->setTagDecorator([
            'decorator' => 'TagDummy',
            'options'   => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf('LaminasTest\Tag\Cloud\TestAsset\TagDummy', $cloud->getTagDecorator());
        $this->assertEquals('bar', $cloud->getTagDecorator()->getFoo());
    }

    public function testGetAndSetTagDecorator()
    {
        $cloud = $this->getCloud();
        $this->assertInstanceOf(HtmlTag::class, $cloud->getTagDecorator());

        $cloud->setTagDecorator(new TestAsset\TagDummy());
        $this->assertInstanceOf(TagDummy::class, $cloud->getTagDecorator());
    }

    public function testSetInvalidTagDecorator()
    {
        $cloud = $this->getCloud();

        $this->expectException('Laminas\Tag\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('DecoratorInterface');
        $cloud->setTagDecorator(new stdClass());
    }

    public function testSetDecoratorPluginManager()
    {
        $decorators = new DecoratorPluginManager(new ServiceManager());

        $cloud = $this->getCloud([], null);
        $cloud->setDecoratorPluginManager($decorators);

        $this->assertSame($decorators, $cloud->getDecoratorPluginManager());
    }

    public function testSetDecoratorPluginManagerViaOptions()
    {
        $decorators = new DecoratorPluginManager(new ServiceManager());
        $cloud      = $this->getCloud(['decoratorPluginManager' => $decorators], null);

        $this->assertSame($decorators, $cloud->getDecoratorPluginManager());
    }

    public function testAppendTagAsArray()
    {
        $cloud = $this->getCloud();
        $list  = $cloud->getItemList();

        $cloud->appendTag([
            'title'  => 'foo',
            'weight' => 1,
        ]);

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testAppendTagAsItem()
    {
        $cloud = $this->getCloud();
        $list  = $cloud->getItemList();

        $cloud->appendTag(new Tag\Item([
            'title'  => 'foo',
            'weight' => 1,
        ]));

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testAppendInvalidTag()
    {
        $cloud = $this->getCloud();

        $this->expectException('Laminas\Tag\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('TaggableInterface');
        $cloud->appendTag('foo');
    }

    public function testSetTagsAsArray()
    {
        $cloud = $this->getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags([
            [
                'title'  => 'foo',
                'weight' => 1,
            ],
            [
                'title'  => 'bar',
                'weight' => 2,
            ]
        ]);

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetTagsAsItem()
    {
        $cloud = $this->getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags([
            new Tag\Item([
                'title'  => 'foo',
                'weight' => 1,
            ]),
            new Tag\Item([
                'title'  => 'bar',
                'weight' => 2,
            ]),
        ]);

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetTagsMixed()
    {
        $cloud = $this->getCloud();
        $list  = $cloud->getItemList();

        $cloud->setTags([
            [
                'title'  => 'foo',
                'weight' => 1,
            ],
            new Tag\Item([
                'title'  => 'bar',
                'weight' => 2,
            ]),
        ]);

        $this->assertEquals('foo', $list[0]->getTitle());
        $this->assertEquals('bar', $list[1]->getTitle());
    }

    public function testSetInvalidTags()
    {
        $cloud = $this->getCloud();

        $this->expectException('Laminas\Tag\Exception\InvalidArgumentException');
        $this->expectExceptionMessage('TaggableInterface');
        $cloud->setTags(['foo']);
    }

    public function testConstructorWithArray()
    {
        $cloud = $this->getCloud([
            'tags' => [
                [
                    'title'  => 'foo',
                    'weight' => 1,
                ],
            ],
        ]);
        $list  = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    /**
     * This test uses ArrayObject, which will have essentially the
     * same behavior as Laminas\Config\Config; the code is looking only
     * for a Traversable.
     */
    public function testConstructorWithConfig()
    {
        $cloud = $this->getCloud(new ArrayObject([
            'tags' => [
                [
                    'title'  => 'foo',
                    'weight' => 1,
                ],
            ],
        ]));
        $list  = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testSetOptions()
    {
        $cloud = $this->getCloud();
        $cloud->setOptions([
            'tags' => [
                [
                    'title'  => 'foo',
                    'weight' => 1,
                ],
            ],
        ]);
        $list = $cloud->getItemList();

        $this->assertEquals('foo', $list[0]->getTitle());
    }

    public function testSkipOptions()
    {
        $this->getCloud(['options' => 'foobar']);
    }

    public function testRender()
    {
        $cloud = $this->getCloud([
            'tags' => [
                [
                    'title'  => 'foo',
                    'weight' => 1,
                ],
                [
                    'title'  => 'bar',
                    'weight' => 3,
                ],
            ],
        ]);

        $expected = '<ul class="laminas-tag-cloud">'
            . '<li><a href="" style="font-size: 10px;">foo</a></li> '
            . '<li><a href="" style="font-size: 20px;">bar</a></li>'
            . '</ul>';

        $this->assertEquals($expected, $cloud->render());
    }

    public function testRenderEmptyCloud()
    {
        $cloud = $this->getCloud();
        $this->assertEquals('', $cloud->render());
    }

    public function testRenderViaToString()
    {
        $cloud    = $this->getCloud([
            'tags' => [
                [
                    'title'  => 'foo',
                    'weight' => 1,
                ],
                [
                    'title'  => 'bar',
                    'weight' => 3,
                ],
            ],
        ]);
        $expected = '<ul class="laminas-tag-cloud">'
            . '<li><a href="" style="font-size: 10px;">foo</a></li> '
            . '<li><a href="" style="font-size: 20px;">bar</a></li>'
            . '</ul>';
        $this->assertEquals($expected, (string)$cloud);
    }

    protected function getCloud($options = null, $setDecoratorPluginManager = true)
    {
        $cloud = new Tag\Cloud($options);

        if ($setDecoratorPluginManager) {
            $decorators = $cloud->getDecoratorPluginManager();
            /*
            $decorators->configure([
                'invokables' => [
                    'CloudDummy' => CloudDummy::class,
                    'TagDummy'   => TagDummy::class
                ]
            ]);
            */
            $smConfig = new SMConfig([
              'invokables' => [
                  'CloudDummy' => CloudDummy::class,
                  'TagDummy'   => TagDummy::class
              ]
            ]);
            $smConfig->configureServiceManager($decorators);
        }

        return $cloud;
    }
}

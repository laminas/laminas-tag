<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Tag\Cloud\Decorator;

use Laminas\Tag\Cloud\Decorator;

/**
 * @group      Laminas_Tag
 * @group      Laminas_Tag_Cloud
 */
class HtmlCloudTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultOutput()
    {
        $decorator = new Decorator\HtmlCloud();

        $this->assertEquals(
            '<ul class="laminas-tag-cloud">foo bar</ul>',
            $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testNestedTags()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHtmlTags(
            array(
                 'span',
                 'div' => array('id' => 'tag-cloud')
            )
        );

        $this->assertEquals(
            '<div id="tag-cloud"><span>foo bar</span></div>',
            $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testSeparator()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setSeparator('-');

        $this->assertEquals(
            '<ul class="laminas-tag-cloud">foo-bar</ul>',
            $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testConstructorWithArray()
    {
        $decorator = new Decorator\HtmlCloud(array(
                                                  'htmlTags'  => array('div'),
                                                  'separator' => ' '
                                             ));

        $this->assertEquals(
            '<div>foo bar</div>', $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testConstructorWithConfig()
    {
        $decorator = new Decorator\HtmlCloud(
            new \Laminas\Config\Config(
                array(
                     'htmlTags'  => array('div'),
                     'separator' => ' '
                )
            )
        );

        $this->assertEquals(
            '<div>foo bar</div>', $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testSetOptions()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setOptions(
            array(
                 'htmlTags'  => array('div'),
                 'separator' => ' '
            )
        );

        $this->assertEquals(
            '<div>foo bar</div>', $decorator->render(
                array(
                     'foo',
                     'bar'
                )
            )
        );
    }

    public function testSkipOptions()
    {
        $decorator = new Decorator\HtmlCloud(array('options' => 'foobar'));
        // In case would fail due to an error
    }

    public function invalidHtmlTagProvider()
    {
        return array(
            array(array('_foo')),
            array(array('&foo')),
            array(array(' foo')),
            array(array(' foo')),
            array(
                array(
                    '_foo' => array(),
                )
            ),
        );
    }

    /**
     * @dataProvider invalidHtmlTagProvider
     */
    public function testInvalidHtmlTagsRaiseAnException($tags)
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHTMLTags($tags);
        $this->setExpectedException(
            'Laminas\Tag\Exception\InvalidElementNameException'
        );
        $decorator->render(array());
    }

    public function invalidAttributeProvider()
    {
        return array(
            array(
                array(
                    'foo' => array(
                        '&bar' => 'baz',
                    ),
                )
            ),
            array(
                array(
                    'foo' => array(
                        ':bar&baz' => 'bat',
                    ),
                )
            ),
            array(
                array(
                    'foo' => array(
                        'bar/baz' => 'bat',
                    ),
                )
            ),
        );
    }

    /**
     * @dataProvider invalidAttributeProvider
     */
    public function testInvalidAttributeNamesRaiseAnException($tags)
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHTMLTags($tags);
        $this->setExpectedException(
            'Laminas\Tag\Exception\InvalidAttributeNameException'
        );
        $decorator->render(array());
    }
}

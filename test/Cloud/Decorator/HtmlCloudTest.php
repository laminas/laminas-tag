<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Tag\Cloud\Decorator;

use Laminas\Tag\Cloud\Decorator;

/**
 * @category   Laminas
 * @package    Laminas_Tag
 * @subpackage UnitTests
 * @group      Laminas_Tag
 * @group      Laminas_Tag_Cloud
 */
class HtmlCloudTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultOutput()
    {
        $decorator = new Decorator\HtmlCloud();

        $this->assertEquals('<ul class="Laminas\Tag\Cloud">foo bar</ul>', $decorator->render(array('foo', 'bar')));
    }

    public function testNestedTags()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHtmlTags(array('span', 'div' => array('id' => 'tag-cloud')));

        $this->assertEquals('<div id="tag-cloud"><span>foo bar</span></div>', $decorator->render(array('foo', 'bar')));
    }

    public function testSeparator()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setSeparator('-');

        $this->assertEquals('<ul class="Laminas\Tag\Cloud">foo-bar</ul>', $decorator->render(array('foo', 'bar')));
    }

    public function testConstructorWithArray()
    {
        $decorator = new Decorator\HtmlCloud(array('htmlTags' => array('div'), 'separator' => ' '));

        $this->assertEquals('<div>foo bar</div>', $decorator->render(array('foo', 'bar')));
    }

    public function testConstructorWithConfig()
    {
        $decorator = new Decorator\HtmlCloud(new \Laminas\Config\Config(array('htmlTags' => array('div'), 'separator' => ' ')));

        $this->assertEquals('<div>foo bar</div>', $decorator->render(array('foo', 'bar')));
    }

    public function testSetOptions()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setOptions(array('htmlTags' => array('div'), 'separator' => ' '));

        $this->assertEquals('<div>foo bar</div>', $decorator->render(array('foo', 'bar')));
    }

    public function testSkipOptions()
    {
        $decorator = new Decorator\HtmlCloud(array('options' => 'foobar'));
        // In case would fail due to an error
    }
}


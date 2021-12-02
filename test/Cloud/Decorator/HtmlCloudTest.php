<?php

declare(strict_types=1);

namespace LaminasTest\Tag\Cloud\Decorator;

use ArrayObject;
use Laminas\Tag\Cloud\Decorator;
use Laminas\Tag\Exception\InvalidAttributeNameException;
use Laminas\Tag\Exception\InvalidElementNameException;
use PHPUnit\Framework\TestCase;

class HtmlCloudTest extends TestCase
{
    public function testDefaultOutput()
    {
        $decorator = new Decorator\HtmlCloud();

        $this->assertEquals(
            '<ul class="laminas-tag-cloud">foo bar</ul>',
            $decorator->render(
                [
                    'foo',
                    'bar',
                ]
            )
        );
    }

    public function testNestedTags()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHtmlTags(
            [
                'span',
                'div' => ['id' => 'tag-cloud'],
            ]
        );

        $this->assertEquals(
            '<div id="tag-cloud"><span>foo bar</span></div>',
            $decorator->render(
                [
                    'foo',
                    'bar',
                ]
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
                [
                    'foo',
                    'bar',
                ]
            )
        );
    }

    public function testConstructorWithArray()
    {
        $decorator = new Decorator\HtmlCloud([
            'htmlTags'  => ['div'],
            'separator' => ' ',
        ]);

        $this->assertEquals(
            '<div>foo bar</div>',
            $decorator->render([
                'foo',
                'bar',
            ])
        );
    }

    /**
     * This test uses ArrayObject, which will have essentially the
     * same behavior as Laminas\Config\Config; the code is looking only
     * for a Traversable.
     */
    public function testConstructorWithConfig()
    {
        $decorator = new Decorator\HtmlCloud(new ArrayObject([
            'htmlTags'  => ['div'],
            'separator' => ' ',
        ]));

        $this->assertEquals(
            '<div>foo bar</div>',
            $decorator->render([
                'foo',
                'bar',
            ])
        );
    }

    public function testSetOptions()
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setOptions([
            'htmlTags'  => ['div'],
            'separator' => ' ',
        ]);

        $this->assertEquals(
            '<div>foo bar</div>',
            $decorator->render([
                'foo',
                'bar',
            ])
        );
    }

    public function testSkipOptions()
    {
        $decorator = new Decorator\HtmlCloud(['options' => 'foobar']);
        // In case would fail due to an error
    }

    /** @psalm-return array<array-key, array{0: string[]|array<string, array>}> */
    public function invalidHtmlTagProvider(): array
    {
        return [
            [['_foo']],
            [['&foo']],
            [[' foo']],
            [[' foo']],
            [
                [
                    '_foo' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidHtmlTagProvider
     */
    public function testInvalidHtmlTagsRaiseAnException(array $tags)
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHTMLTags($tags);
        $this->expectException(InvalidElementNameException::class);
        $decorator->render([]);
    }

    /** @psalm-return array<array-key, array{0: array<string, map>}> */
    public function invalidAttributeProvider(): array
    {
        return [
            [
                [
                    'foo' => [
                        '&bar' => 'baz',
                    ],
                ],
            ],
            [
                [
                    'foo' => [
                        ':bar&baz' => 'bat',
                    ],
                ],
            ],
            [
                [
                    'foo' => [
                        'bar/baz' => 'bat',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidAttributeProvider
     */
    public function testInvalidAttributeNamesRaiseAnException(array $tags)
    {
        $decorator = new Decorator\HtmlCloud();
        $decorator->setHTMLTags($tags);
        $this->expectException(InvalidAttributeNameException::class);
        $decorator->render([]);
    }
}

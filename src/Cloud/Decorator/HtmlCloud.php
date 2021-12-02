<?php // phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName.NotCamelCaps


declare(strict_types=1);

namespace Laminas\Tag\Cloud\Decorator;

use function get_class;
use function gettype;
use function implode;
use function is_array;
use function is_object;
use function sprintf;

/**
 * Simple HTML decorator for clouds
 */
class HtmlCloud extends AbstractCloud
{
    /**
     * List of HTML tags
     *
     * @var array
     */
    protected $htmlTags = [
        'ul' => ['class' => 'laminas-tag-cloud'],
    ];

    /**
     * Separator for the single tags
     *
     * @var string
     */
    protected $separator = ' ';

    /**
     * Set the HTML tags surrounding all tags
     *
     * @param  array $htmlTags
     * @return HtmlCloud
     */
    public function setHTMLTags(array $htmlTags)
    {
        $this->htmlTags = $htmlTags;
        return $this;
    }

    /**
     * Retrieve HTML tag map
     *
     * @return array
     */
    public function getHTMLTags()
    {
        return $this->htmlTags;
    }

    /**
     * Set the separator between the single tags
     *
     * @param  string $separator
     * @return HtmlCloud
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * Get tag separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Defined by Laminas\Tag\Cloud\Decorator\Cloud
     *
     * @param  array $tags
     * @throws Exception\InvalidArgumentException
     * @return string
     */
    public function render($tags)
    {
        if (! is_array($tags)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'HtmlCloud::render() expects an array argument; received "%s"',
                is_object($tags) ? get_class($tags) : gettype($tags)
            ));
        }
        $cloudHTML = implode($this->getSeparator(), $tags);
        $cloudHTML = $this->wrapTag($cloudHTML);
        return $cloudHTML;
    }
}

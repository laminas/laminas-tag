<?php

/**
 * @see       https://github.com/laminas/laminas-tag for the canonical source repository
 * @copyright https://github.com/laminas/laminas-tag/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-tag/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Tag\Cloud\Decorator;

/**
 * Simple HTML decorator for clouds
 *
 * @category  Laminas
 * @package   Laminas_Tag
 */
class HtmlCloud extends AbstractCloud
{
    /**
     * @var string Encoding to use
     */
    protected $encoding = 'UTF-8';

    /**
     * List of HTML tags
     *
     * @var array
     */
    protected $htmlTags = array(
        'ul' => array('class' => 'Laminas\Tag\Cloud')
    );

    /**
     * Separator for the single tags
     *
     * @var string
     */
    protected $separator = ' ';

    /**
     * Get encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set encoding
     *
     * @param string
     * @return HTMLCloud
     */
    public function setEncoding($value)
    {
        $this->encoding = (string) $value;
        return $this;
    }

    /**
     * Set the HTML tags surrounding all tags
     *
     * @param  array $htmlTags
     * @return HTMLCloud
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
     * @param  string
     * @return HTMLCloud
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
        if (!is_array($tags)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'HtmlCloud::render() expects an array argument; received "%s"',
                (is_object($tags) ? get_class($tags) : gettype($tags))
            ));
        }
        $cloudHTML = implode($this->getSeparator(), $tags);

        $enc = $this->getEncoding();
        foreach ($this->getHTMLTags() as $key => $data) {
            if (is_array($data)) {
                $htmlTag    = $key;
                $attributes = '';

                foreach ($data as $param => $value) {
                    $attributes .= ' ' . $param . '="' . htmlspecialchars($value, ENT_COMPAT, $enc) . '"';
                }
            } else {
                $htmlTag    = $data;
                $attributes = '';
            }

            $cloudHTML = sprintf('<%1$s%3$s>%2$s</%1$s>', $htmlTag, $cloudHTML, $attributes);
        }

        return $cloudHTML;
    }
}

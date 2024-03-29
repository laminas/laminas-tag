<?php

declare(strict_types=1);

namespace Laminas\Tag;

use Exception;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Tag\Cloud\Decorator\HtmlCloud;
use Laminas\Tag\Cloud\Decorator\HtmlTag;
use Laminas\Tag\Exception\InvalidArgumentException;
use Traversable;

use function count;
use function gettype;
use function in_array;
use function is_array;
use function is_object;
use function is_string;
use function method_exists;
use function sprintf;
use function strtolower;
use function trigger_error;

use const E_USER_WARNING;

class Cloud
{
    /**
     * DecoratorInterface for the cloud
     *
     * @var Cloud\Decorator\AbstractCloud
     */
    protected $cloudDecorator;

    /**
     * DecoratorInterface for the tags
     *
     * @var Cloud\Decorator\AbstractTag
     */
    protected $tagDecorator;

    /**
     * List of all tags
     *
     * @var ItemList
     */
    protected $tags;

    /**
     * Plugin manager for decorators
     *
     * @var Cloud\DecoratorPluginManager
     */
    protected $decorators;

    /**
     * Option keys to skip when calling setOptions()
     *
     * @var array
     */
    protected $skipOptions = [
        'options',
        'config',
    ];

    /**
     * Create a new tag cloud with options
     *
     * @param  array|Traversable $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Set options from array
     *
     * @param  array $options Configuration for Cloud
     * @return Cloud
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (in_array(strtolower($key), $this->skipOptions)) {
                continue;
            }

            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Set the tags for the tag cloud.
     *
     * $tags should be an array containing single tags as array. Each tag
     * array should at least contain the keys 'title' and 'weight'. Optionally
     * you may supply the key 'url', to which the tag links to. Any additional
     * parameter in the array is silently ignored and can be used by custom
     * decorators.
     *
     * @param  array $tags
     * @throws InvalidArgumentException
     * @return Cloud
     */
    public function setTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->appendTag($tag);
        }
        return $this;
    }

    /**
     * Append a single tag to the cloud
     *
     * @param  TaggableInterface|array $tag
     * @throws InvalidArgumentException
     * @return Cloud
     */
    public function appendTag($tag)
    {
        $tags = $this->getItemList();

        if ($tag instanceof TaggableInterface) {
            $tags[] = $tag;
            return $this;
        }

        if (! is_array($tag)) {
            throw new InvalidArgumentException(sprintf(
                'Tag must be an instance of %s\TaggableInterface or an array; received "%s"',
                __NAMESPACE__,
                is_object($tag) ? $tag::class : gettype($tag)
            ));
        }

        $tags[] = new Item($tag);

        return $this;
    }

    /**
     * Set the item list
     *
     * @return Cloud
     */
    public function setItemList(ItemList $itemList)
    {
        $this->tags = $itemList;
        return $this;
    }

    /**
     * Retrieve the item list
     *
     * If item list is undefined, creates one.
     *
     * @return ItemList
     */
    public function getItemList()
    {
        if (null === $this->tags) {
            $this->setItemList(new ItemList());
        }
        return $this->tags;
    }

    /**
     * Set the decorator for the cloud
     *
     * @param  mixed $decorator
     * @throws InvalidArgumentException
     * @return Cloud
     */
    public function setCloudDecorator($decorator)
    {
        $options = null;

        if (is_array($decorator)) {
            if (isset($decorator['options'])) {
                $options = $decorator['options'];
            }

            if (isset($decorator['decorator'])) {
                $decorator = $decorator['decorator'];
            }
        }

        if (is_string($decorator)) {
            $decorator = $this->getDecoratorPluginManager()->get($decorator, $options);
        }

        if (! $decorator instanceof Cloud\Decorator\AbstractCloud) {
            throw new InvalidArgumentException(
                'DecoratorInterface is no instance of Cloud\Decorator\AbstractCloud'
            );
        }

        $this->cloudDecorator = $decorator;

        return $this;
    }

    /**
     * Get the decorator for the cloud
     *
     * @return Cloud\Decorator\AbstractCloud
     */
    public function getCloudDecorator()
    {
        if (null === $this->cloudDecorator) {
            $this->setCloudDecorator(HtmlCloud::class);
        }
        return $this->cloudDecorator;
    }

    /**
     * Set the decorator for the tags
     *
     * @param  mixed $decorator
     * @throws InvalidArgumentException
     * @return Cloud
     */
    public function setTagDecorator($decorator)
    {
        $options = null;

        if (is_array($decorator)) {
            if (isset($decorator['options'])) {
                $options = $decorator['options'];
            }

            if (isset($decorator['decorator'])) {
                $decorator = $decorator['decorator'];
            }
        }

        if (is_string($decorator)) {
            $decorator = $this->getDecoratorPluginManager()->get($decorator, $options);
        }

        if (! $decorator instanceof Cloud\Decorator\AbstractTag) {
            throw new InvalidArgumentException(
                'DecoratorInterface is no instance of Cloud\Decorator\AbstractTag'
            );
        }

        $this->tagDecorator = $decorator;

        return $this;
    }

    /**
     * Get the decorator for the tags
     *
     * @return Cloud\Decorator\AbstractTag
     */
    public function getTagDecorator()
    {
        if (null === $this->tagDecorator) {
            $this->setTagDecorator(HtmlTag::class);
        }
        return $this->tagDecorator;
    }

    /**
     * Set plugin manager for use with decorators
     *
     * @return Cloud
     */
    public function setDecoratorPluginManager(Cloud\DecoratorPluginManager $decorators)
    {
        $this->decorators = $decorators;
        return $this;
    }

    /**
     * Get the plugin manager for decorators
     *
     * @return Cloud\DecoratorPluginManager
     */
    public function getDecoratorPluginManager()
    {
        if ($this->decorators === null) {
            $this->decorators = new Cloud\DecoratorPluginManager(new ServiceManager());
        }

        return $this->decorators;
    }

    /**
     * Render the tag cloud
     *
     * @return string
     */
    public function render()
    {
        $tags = $this->getItemList();

        if (count($tags) === 0) {
            return '';
        }

        $tagsResult = $this->getTagDecorator()->render($tags);
        return $this->getCloudDecorator()->render($tagsResult);
    }

    /**
     * Render the tag cloud
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            $message = sprintf(
                "Exception caught by tag cloud: %s\nStack Trace:\n%s",
                $e->getMessage(),
                $e->getTraceAsString()
            );
            trigger_error($message, E_USER_WARNING);
            return '';
        }
    }
}

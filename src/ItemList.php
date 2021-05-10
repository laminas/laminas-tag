<?php

namespace Laminas\Tag;

use ArrayAccess;
use Countable;
use Laminas\Tag\Exception\InvalidArgumentException;
use Laminas\Tag\Exception\OutOfBoundsException;
use SeekableIterator;

class ItemList implements Countable, SeekableIterator, ArrayAccess
{
    /**
     * Items in this list
     *
     * @var array
     */
    protected $items = [];

    /**
     * Count all items
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Spread values in the items relative to their weight
     *
     * @param  array $values
     * @throws InvalidArgumentException When value list is empty
     * @return void
     */
    public function spreadWeightValues(array $values)
    {
        // Don't allow an empty value list
        if (count($values) === 0) {
            throw new InvalidArgumentException('Value list may not be empty');
        }

        // Re-index the array
        $values = array_values($values);

        // If just a single value is supplied simply assign it to to all tags
        if (count($values) === 1) {
            foreach ($this->items as $item) {
                $item->setParam('weightValue', $values[0]);
            }
        } else {
            // Calculate min- and max-weight
            $minWeight = null;
            $maxWeight = null;

            foreach ($this->items as $item) {
                if ($minWeight === null && $maxWeight === null) {
                    $minWeight = $item->getWeight();
                    $maxWeight = $item->getWeight();
                } else {
                    $minWeight = min($minWeight, $item->getWeight());
                    $maxWeight = max($maxWeight, $item->getWeight());
                }
            }

            // Calculate the thresholds
            $steps      = count($values);
            $delta      = ($maxWeight - $minWeight) / ($steps - 1);
            $thresholds = [];

            for ($i = 0; $i < $steps; $i++) {
                $thresholds[$i] = floor(100 * log(($minWeight + $i * $delta) + 2));
            }

            // Then assign the weight values
            foreach ($this->items as $item) {
                $threshold = floor(100 * log($item->getWeight() + 2));

                for ($i = 0; $i < $steps; $i++) {
                    if ($threshold <= $thresholds[$i]) {
                        $item->setParam('weightValue', $values[$i]);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Seek to an absolute position
     *
     * @param  int $index
     * @throws OutOfBoundsException When the seek position is invalid
     * @return void
     */
    public function seek($index)
    {
        $this->rewind();
        $position = 0;

        while ($position < $index && $this->valid()) {
            $this->next();
            $position++;
        }

        if (! $this->valid()) {
            throw new OutOfBoundsException('Invalid seek position');
        }
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Move forward to next element
     *
     * @return mixed
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * Return the key of the current element
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * Check if there is a current element after calls to rewind() or next()
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->current() !== false);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * Check if an offset exists
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Get the value of an offset
     *
     * @param  mixed $offset
     * @return TaggableInterface
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Append a new item
     *
     * @param  mixed          $offset
     * @param  TaggableInterface $item
     * @throws OutOfBoundsException When item does not implement Laminas\Tag\TaggableInterface
     * @return void
     */
    public function offsetSet($offset, $item)
    {
        // We need to make that check here, as the method signature must be
        // compatible with ArrayAccess::offsetSet()
        if (! ($item instanceof TaggableInterface)) {
            throw new OutOfBoundsException('Item must implement Laminas\Tag\TaggableInterface');
        }

        if ($offset === null) {
            $this->items[] = $item;
        } else {
            $this->items[$offset] = $item;
        }
    }

    /**
     * Unset an item
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}

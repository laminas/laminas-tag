<?php

namespace Laminas\Tag;

interface TaggableInterface
{
    /**
     * Get the title of the tag
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get the weight of the tag
     *
     * @return float
     */
    public function getWeight();

    /**
     * Set a parameter
     *
     * @param string $name
     * @param string $value
     */
    public function setParam($name, $value);

    /**
     * Get a parameter
     *
     * @param  string $name
     * @return mixed
     */
    public function getParam($name);
}

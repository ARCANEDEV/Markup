<?php namespace Arcanedev\Markup\Contracts\Entities;

interface TagInterface
{
    /**
     * Make a Tag
     *
     * @param string $type
     * @param array  $attributes
     * @param mixed  $parent
     *
     * @return TagInterface
     */
    public static function make($type, array $attributes = [], $parent = null);

    /**
     * Get Type
     *
     * @return string
     */
    public function getType();

    /**
     * Set many attributes
     *
     * @param array $attributes
     *
     * @return TagInterface
     */
    public function attrs(array $attributes);

    /**
     * Get Attributes
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get Attributes
     *
     * @return array
     */
    public function renderAttributes();

    /**
     * Define an attribute
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return TagInterface
     */
    public function set($name, $value);

    /**
     * Alias to method "set"
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return TagInterface
     */
    public function attr($name, $value);

    /**
     * Get Text
     *
     * @return string
     */
    public function getText();

    /**
     * Render Tag and its elements
     *
     * @return string
     */
    public function render();

    /**
     * Render tag elements
     *
     * @return string
     */
    public function renderElements();

    /**
     * Add element at an existing Markup
     *
     * @param TagInterface|string $tag
     * @param array      $attributes
     *
     * @return TagInterface
     */
    public function addElement($tag, array $attributes = []);

    /**
     * Define text content
     *
     * @param string $value
     *
     * @return TagInterface
     */
    public function text($value);

    /**
     * Check if tag is a text object
     *
     * @return bool
     */
    public function isTextType();

    /**
     * Check if has attributes
     *
     * @return bool
     */
    public function hasAttributes();
}

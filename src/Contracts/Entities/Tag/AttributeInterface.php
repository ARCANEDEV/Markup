<?php namespace Arcanedev\Markup\Contracts\Entities\Tag;

interface AttributeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get attribute name
     *
     * @return string
     */
    public function getName();

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Attribute
     *
     * @param string       $name
     * @param string|array $content
     *
     * @return AttributeInterface
     */
    public static function make($name, $content);

    /**
     * Add content to attribute
     *
     * @param string|array $content
     *
     * @return AttributeInterface
     */
    public function addContent($content);

    /**
     * Convert attribute to string
     *
     * @return string
     */
    public function toString();

    /* ------------------------------------------------------------------------------------------------
     |  Check Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if attribute is empty
     *
     * @return bool
     */
    public function isEmpty();
}

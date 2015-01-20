<?php namespace Arcanedev\Markup\Contracts\Entities\Tag;

use Arcanedev\Markup\Entities\Tag\Attribute;
use Arcanedev\Markup\Entities\Tag\AttributeCollection;

interface AttributeCollectionInterface
{
    /**
     * Add Attribute
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return $this
     */
    public function add($name, $value);

    /**
     * Add many attributes to collection
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function addMany(array $attributes);

    public function render();

    /**
     * Get Attribute from collection
     *
     * @param string $name
     *
     * @return Attribute|null
     */
    public function getAttr($name);

    /**
     * Forget an attribute value
     *
     * @param  string       $name
     * @param  string|array $value
     *
     * @return AttributeCollection
     */
    public function forgetValue($name, $value);

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray();

    /**
     * Check if collection is empty
     *
     * @return bool
     */
    public function isEmpty();
}
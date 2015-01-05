<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Support\Collection;

class AttributeCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $items = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add Attribute
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function add($name, $value)
    {
        if ($this->has($name)) {
            $this->updateAttribute($name, $value);
        }
        else {
            $this->makeAttribute($name, $value);
        }

        return $this;
    }

    /**
     * Add many attributes to collection
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function addMany(array $attributes)
    {
        if (count($attributes) > 0) {
            foreach($attributes as $name => $value) {
                $this->add($name, $value);
            }
        }

        return $this;
    }

    public function render()
    {
        $attributes = $this->each(function($attribute) {
            /** @var Attribute $attribute */
            return $attribute->render();
        });

        return implode(' ', array_filter($attributes->toArray()));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Attribute from collection
     *
     * @param string $name
     *
     * @return Attribute|null
     */
    public function getAttr($name)
    {
        return $this->get($name, null);
    }

    /**
     * Adding a new attribute to collection
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return AttributeCollection
     */
    private function makeAttribute($name, $value)
    {
        $attribute = Attribute::make($name, $value);

        $this->put($attribute->getName(), $attribute);

        return $this;
    }

    /**
     * Updating existing attribute
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return $this
     */
    private function updateAttribute($name, $value)
    {
        $attribute = $this->getAttr($name);

        if (! is_null($attribute)) {
            $attribute->addContent($value);

            $this->put($name, $attribute);
        }

        return $this;
    }

    public function forgetValue($name, $value)
    {
        if ($this->has($name)) {
            $attr = $this->getAttr($name);

            $attr->forgetValue($value);
        }

        return $this;
    }

    public function toArray()
    {
        $attributes = $this->each(function($attribute) {
            /** @var Attribute $attribute */
            return $attribute->values();
        });

        return $attributes->toArray();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if collection is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }
}

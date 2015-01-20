<?php namespace Arcanedev\Markup\Entities\Traits;

use Arcanedev\Markup\Entities\Tag\AttributeCollection;

trait Attributeable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var AttributeCollection */
    protected $attributes;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Init Attribute Collection
     */
    private function initAttributes()
    {
        $this->attributes = new AttributeCollection;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set many attributes
     *
     * @param  array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes->addMany($attributes);

        return $this;
    }

    /**
     * Set many attributes (Alias)
     *
     * @param  array $attributes
     *
     * @return self
     */
    public function attrs(array $attributes)
    {
        return $this->setAttributes($attributes);
    }

    /**
     * Get Attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes->toArray();
    }

    /**
     * Set Attribute
     *
     * @param  string       $name
     * @param  string|array $value
     *
     * @return self
     */
    public function attr($name, $value)
    {
        $this->attributes->add($name, $value);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Attributes
     *
     * @return string
     */
    public function renderAttributes()
    {
        return $this->attributes->render();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if has attributes
     *
     * @return bool
     */
    public function hasAttributes()
    {
        return ! empty($this->attributes) and
        ! $this->attributes->isEmpty();
    }
}

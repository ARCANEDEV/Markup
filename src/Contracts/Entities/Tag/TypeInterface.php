<?php namespace Arcanedev\Markup\Contracts\Entities\Tag;

use Arcanedev\Markup\Entities\Tag\Type;

interface TypeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName($name);

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if type is empty
     *
     * @return bool
     */
    public function isEmpty();
}
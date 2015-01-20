<?php namespace Arcanedev\Markup\Entities\Traits;

use Arcanedev\Markup\Entities\Tag\Type;

trait Typeable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Type */
    private $type;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type->getName();
    }

    /**
     * Set type
     *
     * @param string $type
     */
    private function setType($type)
    {
        $this->type = new Type($type);
    }
}

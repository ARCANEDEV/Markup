<?php namespace Arcanedev\Markup\Entities\Tag;

use Arcanedev\Markup\Contracts\Entities\Tag\TypeInterface;
use Arcanedev\Markup\Exceptions\InvalidTypeException;

class Type implements TypeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $name;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct($name = '')
    {
        $this->setName($name);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName($name)
    {
        $this->checkName($name);

        $this->name = $name;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check name
     *
     * @param string $name
     *
     * @throws InvalidTypeException
     */
    private function checkName(&$name)
    {
        if (! is_string($name)) {
            throw new InvalidTypeException(
                'The tag type must be a string, ' . gettype($name) . ' is given.'
            );
        }

        $name = strtolower(trim($name));
    }

    /**
     * Check if type is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->name);
    }
}

<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Contracts\Entities\AttributeInterface;

use Arcanedev\Markup\Exceptions\Exception;
use Arcanedev\Markup\Exceptions\InvalidTypeException;

class Attribute implements AttributeInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $name;

    /** @var array */
    protected $values;

    /** @var bool */
    protected $singleValued;

    /** @var array */
    private $singles = [
        'id', 'name', 'href', 'src', 'title', 'alt', 'width', 'height', 'disabled', 'property', 'content'
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     *
     * @param string       $name
     * @param string|array $values
     */
    public function __construct($name, $values)
    {
        $this->init();

        $this->setName($name);
        $this->setValues($values);
    }

    /**
     * Init Object
     */
    private function init()
    {
        $this->name         = '';
        $this->values       = [];
        $this->singleValued = false;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get attribute name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set attribute name
     *
     * @param string $name
     *
     * @return $this
     */
    private function setName($name)
    {
        $this->checkName($name);

        $this->name         = $name;
        $this->singleValued = in_array($name, $this->singles);

        return $this;
    }

    /**
     * Get attribute values
     *
     * @return string
     */
    public function values()
    {
        return $this->values;
    }

    /**
     * Convert attribute values to string
     *
     * @return string
     */
    public function getValues()
    {
        return implode(' ', $this->values);
    }

    /**
     * Set attribute content
     *
     * @param string|array $values
     *
     * @return $this
     */
    private function setValues($values)
    {
        $this->checkContent($values);

        if ($this->isSingleValued()) {
            return $this->addValue($values[0], true);
        }

        return $this->addManyValues($values);
    }

    /**
     * Add many values to values property
     *
     * @param array $values
     *
     * @return Attribute
     */
    private function addManyValues($values)
    {
        foreach($values as $value) {
            $this->addValue($value);
        }

        return $this;
    }

    /**
     * Add value to values property
     *
     * @param string $value
     * @param bool   $erase
     *
     * @return Attribute
     */
    private function addValue($value, $erase = false)
    {
        if ($erase) {
            $this->values = [];
        }

        if (! in_array($value, $this->values)) {
            $this->values[] = $value;
        }

        return $this;
    }

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
     * @return Attribute
     */
    public static function make($name, $content)
    {
        return new self($name, $content);
    }

    /**
     * Add content to attribute
     *
     * @param string|array $content
     *
     * @return Attribute
     */
    public function addContent($content)
    {
        $this->setValues($content);

        return $this;
    }

    /**
     * Remove a value from attribute
     *
     * @param string $value
     *
     * @return $this
     */
    public function forgetValue($value)
    {
        $key = array_search($value, $this->values);

        if ($key !== false) {
            unset($this->values[$key]);
        }

        return $this;
    }

    /**
     * Convert attribute to string
     *
     * @return string
     */
    public function render()
    {
        return $this->toString();
    }

    /**
     * Convert attribute to string
     *
     * @return string
     */
    public function toString()
    {
        return ! $this->isEmpty()
            ? $this->getName() . '="' . $this->getValues() . '"'
            : '';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if attribute is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->name) or empty($this->values);
    }

    /**
     * Check if attribute is single valued
     *
     * @return bool
     */
    private function isSingleValued()
    {
        return $this->singleValued;
    }

    /**
     * Check attribute name
     *
     * @param string $name
     *
     * @throws Exception
     * @throws InvalidTypeException
     */
    private function checkName(&$name)
    {
        if (! is_string($name)) {
            throw new InvalidTypeException(
                'The name must be a string value, ' . gettype($name) . ' is given.'
            );
        }

        $name = strtolower(trim($name));

        if (empty($name)) {
            throw new Exception('The attribute name must not be empty.');
        }
    }

    /**
     * Check attribute content
     *
     * @param string|array $content
     *
     * @throws InvalidTypeException
     */
    private function checkContent(&$content)
    {
        if (! is_array($content) and ! is_string($content)) {
            throw new InvalidTypeException(
                'The content must be an array or string value, ' . gettype($content) . ' is given.'
            );
        }

        if (is_string($content) and ! $this->isSingleValued()) {
            $content = explode(' ', $content);
        }

        $content = is_array($content)
            ? array_map('trim', $content)
            : [$content];
    }
}

<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Builder;
use Arcanedev\Markup\Contracts\Entities\TagInterface;
use Arcanedev\Markup\Exceptions\InvalidTypeException;

class Tag implements TagInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    private $type;

    /** @var AttributeCollection */
    protected $attributes;

    /** @var string */
    protected $text;

    /** @var Tag */
    private $top;

    /** @var Tag */
    private $parent;

    /** @var array */
    private $elements;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Tag Constructor
     *
     * @param string   $type
     * @param array    $attributes
     * @param Tag|null $parent
     */
    public function __construct($type, array $attributes = [], $parent = null)
    {
        $this->init();
        $this->setType($type);
        $this->setAttributes($attributes);
        $this->setParent($parent);
    }

    /**
     * Init Tag Object
     */
    private function init()
    {
        $this->parent     = null;
        $this->attributes = new AttributeCollection;
        $this->elements   = [];
        $this->text       = '';
    }

    /**
     * Make a Tag
     *
     * @param string $type
     * @param array  $attributes
     * @param mixed  $parent
     *
     * @return Tag
     */
    public static function make($type, array $attributes = [], $parent = null)
    {
        return new self($type, $attributes, $parent);
    }

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
        return $this->type;
    }

    /**
     * Set Type
     *
     * @param mixed $type
     *
     * @return Tag
     */
    private function setType($type)
    {
        $this->checkType($type);

        $this->type = $type;

        return $this;
    }

    /**
     * Set many attributes
     *
     * @param array $attributes
     *
     * @return Tag
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes->addMany($attributes);

        return $this;
    }

    /**
     * Set many attributes (Alias)
     *
     * @param array $attributes
     *
     * @return Tag
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
     * Get Attributes
     *
     * @return array
     */
    public function renderAttributes()
    {
        return $this->attributes->render();
    }

    /**
     * Define an attribute
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return Tag
     */
    public function set($name, $value)
    {
        $this->attributes->add($name, $value);

        return $this;
    }

    /**
     * Alias to method "set"
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return Tag
     */
    public function attr($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Get parent Tag
     *
     * @return Tag
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set Parent
     *
     * @param Tag $parent
     *
     * @return Tag
     */
    private function setParent(&$parent)
    {
        if (! is_null($parent)) {
            $this->parent = $parent;
        }

        return $this;
    }

    /**
     * Get Text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Tag
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render Tag and its elements
     *
     * @return string
     */
    public function render()
    {
        return Builder::make($this);
    }

    /**
     * Render tag elements
     *
     * @return string
     */
    public function renderElements()
    {
        if (count($this->elements) == 0) {
            return '';
        }

        $output = array_map(function($tag) {
            /** @var Tag $tag */
            return $tag->render();
        }, $this->elements);

        return implode('', $output);
    }

    /**
     * Add element at an existing Markup
     *
     * @param Tag|string $tag
     * @param array      $attributes
     *
     * @return Tag
     */
    public function addElement($tag, array $attributes = [])
    {
        if ($this->isTagObject($tag)) {
            $htmlTag      = $tag;
            $htmlTag->top = $this->top;
            $htmlTag->attrs($attributes);
        }
        else {
            $parent     = is_null($this->parent) ? $this : $this->parent;
            $htmlTag    = self::make($tag, $attributes, $parent);
        }

        $this->elements[] = $htmlTag;

        return $htmlTag;
    }

    /**
     * Define text content
     *
     * @param string $value
     *
     * @return Tag
     */
    public function text($value)
    {
        $this->addElement('')->setText($value);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if tag is a text object
     *
     * @return bool
     */
    public function isTextType()
    {
        $type = $this->getType();
        $text = $this->getText();

        return empty($type) and ! empty($text);
    }

    /**
     * Check Type
     *
     * @param string $type
     *
     * @throws InvalidTypeException
     */
    private function checkType(&$type)
    {
        if (! is_string($type)) {
            throw new InvalidTypeException(
                'The type tag must be a string, '.gettype($type) . ' is given.'
            );
        }

        $type = strtolower(trim($type));
    }

    /**
     * Check if has attributes
     *
     * @return bool
     */
    public function hasAttributes()
    {
        return ! $this->attributes->isEmpty();
    }

    /**
     * Check if it's a tag object
     *
     * @param mixed $tag
     *
     * @return bool
     */
    private function isTagObject($tag)
    {
        return is_object($tag) and
               get_class($tag) === get_class($this);
    }
}

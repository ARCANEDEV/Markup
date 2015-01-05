<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Builder;

class Tag
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
    protected $text       = '';

    /** @var Tag */
    private $top;

    /** @var Tag */
    private $parent;

    private $builder;
    private $elements;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string $type
     * @param array  $attributes
     * @param mixed  $parent
     */
    public function __construct($type, array $attributes = [], $parent = null)
    {
        $this->setType($type);

        $this->attributes = new AttributeCollection;
        $this->attrs($attributes);
        $this->setParent($parent);

        $this->elements = [];
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
    public function attrs(array $attributes)
    {
        $this->attributes->addMany($attributes);

        return $this;
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
     * Set Parent
     *
     * @param Tag $parent
     *
     * @return Tag
     */
    private function setParent(&$parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return Tag
     */
    private function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function render()
    {
        return Builder::make($this);
    }

    public function contentToString()
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

    public function getText()
    {
        return $this->text;
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
    public function isTextType()
    {
        $type = $this->getType();
        $text = $this->getText();

        return empty($type) and ! empty($text);
    }

    private function checkType($type)
    {
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
        return is_object($tag) and get_class($tag) === get_class($this);
    }
}

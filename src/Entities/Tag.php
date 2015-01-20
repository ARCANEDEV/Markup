<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Contracts\Entities\TagInterface;
use Arcanedev\Markup\Entities\Tag\AttributeCollection;
use Arcanedev\Markup\Entities\Tag\ElementCollection;
use Arcanedev\Markup\Entities\Tag\Type;
use Arcanedev\Markup\Support\Builder;

class Tag implements TagInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Type */
    private $type;

    /** @var string */
    protected $text;

    /** @var Tag */
    private $top;

    /** @var Tag */
    private $parent;

    /** @var AttributeCollection */
    protected $attributes;

    /** @var ElementCollection */
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
        $this->parent     = null;
        $this->attributes = new AttributeCollection;
        $this->elements   = new ElementCollection;
        $this->text       = '';

        $this->type = new Type($type);
        $this->setAttributes($attributes);
        $this->setParent($parent);
    }

    /**
     * Make a Tag
     *
     * @param  string   $type
     * @param  array    $attributes
     * @param  Tag|null $parent
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
        return $this->type->getName();
    }

    /**
     * Set many attributes
     *
     * @param  array $attributes
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
     * @param  array $attributes
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
     * @return string
     */
    public function renderAttributes()
    {
        return $this->attributes->render();
    }

    /**
     * Define an attribute
     *
     * @param  string       $name
     * @param  string|array $value
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
     * @param  string       $name
     * @param  string|array $value
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
     * @param  Tag|null $parent
     *
     * @return Tag
     */
    private function setParent(&$parent)
    {
        if (! is_null($parent)) {
            $this->parent = $parent;

            $this->parent->elements->add($this);
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

    /**
     * Magic method to render Tag
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
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
     * Define text content
     *
     * @param  string $value
     *
     * @return Tag
     */
    public function text($value)
    {
        $this->addElement('')->setText($value);

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Elements Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render tag elements
     *
     * @return string
     */
    public function renderElements()
    {
        return $this->hasElements()
            ? $this->elements->render()
            : '';
    }

    /**
     * Add element at an existing Markup
     *
     * @param  Tag|string $tag
     * @param  array      $attributes
     *
     * @return Tag
     */
    public function addElement($tag, array $attributes = [])
    {
        if ($tag instanceof self) {
            $htmlTag      = $tag;
            $htmlTag->top = $this->top;
            $htmlTag->attrs($attributes);
            $this->elements->add($htmlTag);

            return $htmlTag;
        }

        return self::make(
            $tag,
            $attributes,
            $this->hasParent() ? $this->parent : $this
        );
    }

    /**
     * Get Tag elements count
     *
     * @return int
     */
    public function countElements()
    {
        return count($this->elements);
    }

    /**
     * Return first child of parent of current object
     *
     * @return Tag|null
     */
    public function getFirst()
    {
        $element = null;

        if (
            $this->hasParent() and
            $this->parent->hasElements()
        ) {
            $element = $this->parent->elements[0];
        }

        return $element;
    }

    /**
     * Get last child of parent of current object
     *
     * @return Tag|null
     */
    public function getNext()
    {
        return $this->isParentHasElements()
            ? $this->parent->elements->getNext($this)
            : null;
    }

    /**
     * Return last child of parent of current object
     *
     * @return Tag|null
     */
    public function getPrevious()
    {
        return $this->isParentHasElements()
            ? $this->parent->elements->getPrevious($this)
            : null;
    }

    /**
     * Get last child of parent of current object
     *
     * @return Tag|null
     */
    public function getLast()
    {
        return $this->isParentHasElements()
            ? $this->parent->elements->last()
            : null;
    }

    /**
     * Remove element from parent
     *
     * @return Tag|null
     */
    public function remove()
    {
        return $this->hasParent()
            ? $this->parent->removeElement($this)
            : null;
    }

    /**
     * Remove an element
     *
     * @param $tag
     *
     * @return Tag|null
     */
    public function removeElement($tag)
    {
        list($elements, $deleted) = $this->elements->remove($tag);
        $this->elements = $elements;

        return $deleted ? $this : null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if Tag has parent
     *
     * @return bool
     */
    public function hasParent()
    {
        return ! is_null($this->parent);
    }

    /**
     * Check if Parent Tag has elements
     *
     * @return bool
     */
    private function isParentHasElements()
    {
        return $this->hasParent() and
               $this->parent->hasElements();
    }

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

    /**
     * Check if Tag has elements
     *
     * @return bool
     */
    public function hasElements()
    {
        return $this->countElements() > 0;
    }

    /**
     * Check if tag is a text object
     *
     * @return bool
     */
    public function isTextType()
    {
        $text = $this->getText();

        return $this->type->isEmpty() and ! empty($text);
    }
}

<?php namespace Arcanedev\Markup\Entities;

use Arcanedev\Markup\Support\Builder;
use Arcanedev\Markup\Exceptions\InvalidTypeException;

use Arcanedev\Markup\Contracts\Entities\TagInterface;

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
     * @param Tag|null $parent
     *
     * @return Tag
     */
    private function setParent(&$parent)
    {
        if (! is_null($parent)) {
            $this->parent = $parent;

            $this->parent->elements[] = &$this;
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
        if (! $this->hasElements()) {
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
            $this->elements[] = $htmlTag;
        }
        else {
            $parent     = $this->hasParent() ? $this->parent : $this;
            $htmlTag    = self::make($tag, $attributes, $parent);
        }

        return $htmlTag;
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
        if (
            ! $this->hasParent() or
            ! $this->parent->hasElements()
        ) {
            return null;
        }

        $next = null;
        $find = false;

        foreach ($this->parent->elements as $elt) {
            if ($find) {
                $next = &$elt;
                break;
            }

            if ($elt == $this) {
                $find = true;
            }
        }

        return $next;
    }

    /**
     * Return last child of parent of current object
     *
     * @return Tag|null
     */
    public function getPrevious()
    {
        if (
            ! $this->hasParent() or
            ! $this->parent->hasElements()
        ) {
            return null;
        }

        $prev = null;
        $find = false;

        foreach ($this->parent->elements as $elt) {
            if ($elt == $this) {
                $find = true;
                break;
            }

            if (! $find) {
                $prev = $elt;
            }
        }

        return $prev;
    }

    /**
     * Get last child of parent of current object
     *
     * @return Tag|null
     */
    public function getLast()
    {
        $element = null;

        if (
            $this->hasParent() and
            $this->parent->hasElements()
        ) {
            $count   = $this->parent->countElements();
            $element = $this->parent->elements[$count - 1];
        }

        return $element;
    }

    /**
     * Remove element from parent
     *
     * @return Tag|null
     */
    public function remove()
    {
        if ($this->hasParent()) {
            return $this->parent->removeElement($this);
        }

        return null;
    }

    public function removeElement($tag)
    {
        $deleted = null;

        if (! $this->hasElements()) {
            return $deleted;
        }

        foreach ($this->elements as $key => $value) {
            if ($this->elements[$key] == $tag) {
                unset($this->elements[$key]);

                $deleted = $this;
                break;
            }
        }

        return $deleted;
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
     * Check if has attributes
     *
     * @return bool
     */
    public function hasAttributes()
    {
        return ! empty($this->attributes) and ! $this->attributes->isEmpty();
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
        $type = $this->getType();
        $text = $this->getText();

        return empty($type) and ! empty($text);
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
        return $tag instanceof self;
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
}

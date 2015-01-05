<?php namespace Arcanedev\Markup;

use Arcanedev\Markup\Contracts\MarkupInterface;
use Arcanedev\Markup\Entities\AttributeCollection;

class Markup implements MarkupInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    protected $tag;

    /** @var Markup */
    protected $_top;

    /** @var Markup */
    protected $_parent;

    /** @var AttributeCollection */
    public $attributes;

    protected $content;

    protected $text       = '';

    protected $autoClosed = false;

    /** @var array */
    protected $autoCloseTags = [
        'img', 'br', 'hr', 'input', 'area', 'link', 'meta', 'param'
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    protected function __construct($tag, $top = null)
    {
        $this->setTag($tag);
        $this->_top       =& $top;
        $this->attributes = new AttributeCollection;
        $this->content    = [];
        $this->text       = '';

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set Tag
     *
     * @param string $tag
     *
     * @return Markup
     */
    protected function setTag($tag)
    {
        $this->tag = $tag;

        $this->setAutoClosed($tag);

        return $this;
    }

    /**
     * Set auto closed tag
     *
     * @param string $tag
     *
     * @return Markup
     */
    private function setAutoClosed($tag)
    {
        $this->autoClosed = in_array($tag, $this->autoCloseTags);

        return $this;
    }

    /**
     * Set many attributes
     *
     * @param array $attributes
     *
     * @return Markup
     */
    public function attrs(array $attributes)
    {
        $this->attributes->addMany($attributes);

        return $this;
    }

    /**
     * Define an attribute
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return Markup
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
     * @return Markup
     */
    public function attr($name, $value)
    {
        return $this->set($name, $value);
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
     * Set Text
     *
     * @param string $text
     *
     * @return $this
     */
    private function setText($text = '')
    {
        $this->text = $text;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new Markup
     *
     * @param string $tag
     *
     * @return Markup
     */
    public static function make($tag = '')
    {
        return new self($tag);
    }

    /**
     * Shortcut to set('id', $value)
     *
     * @param string $value
     *
     * @return Markup
     */
    public function id($value)
    {
        return $this->set('id', $value);
    }

    /**
     * Add a class
     *
     * @param string $value
     *
     * @return Markup
     */
    public function addClass($value)
    {
        return $this->set('class', $value);
    }

    /**
     * Remove a class
     *
     * @param string $value
     *
     * @return Markup
     */
    public function removeClass($value)
    {
        $this->attributes->forgetValue('class', $value);

        return $this;
    }

    /**
     * Add element at an existing Markup
     *
     * @param Markup|string $tag
     *
     * @return Markup
     */
    public function addElement($tag)
    {
        $htmlTag = null;

        if (is_object($tag) and get_class($tag) == get_class($this)) {
            $htmlTag         = $tag;
            $htmlTag->_top   = $this->_top;

        }
        else {
            $class           = get_class($this);
            $htmlTag         = new $class($tag, (is_null($this->_top) ? $this : $this->_top));
        }

        $this->content[] = $htmlTag;
        $htmlTag->_parent = &$this;

        return $htmlTag;
    }


    /**
     * Define text content
     *
     * @param string $value
     *
     * @return Markup
     */
    public function text($value)
    {
        $this->addElement('')->setText($value);

        return $this;
    }

    /**
     * Return parent of current element
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Return first child of parent of current object
     */
    public function getFirst()
    {
        return ! is_null($this->_parent)
            ? $this->_parent->content[0]
            : null;
    }

    /**
     * Return last child of parent of current object
     *
     * @return Markup
     */
    public function getPrevious()
    {
        $prev = null;

        if (! is_null($this->_parent)) {
            $find = false;

            foreach ($this->_parent->content as $c) {
                if ($c == $this) {
                    $find = true;
                    break;
                }

                if (! $find) {
                    $prev = $c;
                }
            }
        }

        return $prev;
    }

    /**
     * Get last child of parent of current object
     *
     * @return Markup
     */
    public function getNext()
    {
        $next = null;
        $find = false;

        if (! is_null($this->_parent)) {
            foreach ($this->_parent->content as $c) {
                if ($find) {
                    $next = &$c;
                    break;
                }

                if ($c == $this) {
                    $find = true;
                }
            }
        }

        return $next;
    }

    /**
     * Get last child of parent of current object
     *
     * @return Markup
     */
    public function getLast()
    {
        return ! is_null($this->_parent)
            ? $this->_parent->content[count($this->_parent->content) - 1]
            : null;
    }

    /**
     * Return parent or null
     *
     * @return Markup
     */
    public function remove()
    {
        $parent = $this->_parent;

        if (is_null($parent)) {
            return null;
        }

        foreach ($parent->content as $key => $value) {
            if ($parent->content[$key] == $this) {
                unset($parent->content[$key]);

                return $parent;
            }
        }

        return null;
    }

    /**
     * Generation method
     *
     * @return string
     */
    public function __toString()
    {
        return is_null($this->_top)
            ? $this->toString()
            : $this->_top->toString();
    }

    /**
     * Generation method
     *
     * @return string
     */
    public function toString()
    {
        $string = '';

        if (! empty($this->tag)) {
            $string .=  '<' . $this->tag;
            $string .= $this->attributesToString();

            $string .= $this->autoClosed
                ? '/>' . chr(13) . chr(10) . chr(9)
                :  '>' . $this->contentToString() . '</' . $this->tag . '>';
        }
        else {
            $string .= $this->text;
            $string .= $this->contentToString();
        }

        return $string;
    }

    /**
     * Return current list of attribute as a string
     * > example : $name="$val" $name="$val2"
     *
     * @return string
     */
    protected function attributesToString()
    {
        return $this->hasAttributes()
            ? ' ' . $this->attributes->render()
            : '';
    }

    /**
     * Convert contents to string
     *
     * @return string
     */
    protected function contentToString()
    {
        $string = '';

        if (is_null($this->content)) {
            return $string;
        }

        foreach ($this->content as $c) {
            /** @var Markup $c */
            $string .= chr(13) . chr(10) . chr(9) . $c->toString();
        }

        return $string;
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
    private function hasAttributes()
    {
        return ! $this->attributes->isEmpty();
    }
}

<?php namespace Arcanedev\Markup\Support;

use Arcanedev\Markup\Contracts\BuilderInterface;
use Arcanedev\Markup\Contracts\Entities\TagInterface;

class Builder implements BuilderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    protected static $autoCloseTags = [
        'img', 'br', 'hr', 'input', 'area', 'link', 'meta', 'param'
    ];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render a Tag and its elements
     *
     * @param TagInterface $tag
     *
     * @return string
     */
    public static function make(TagInterface $tag)
    {
        if (
            $tag->getType() === '' and
            $tag->getText() !== ''
        ) {
            return $tag->getText();
        }

        return self::isAutoClosed($tag->getType())
            ? self::open($tag, true)
            : self::open($tag) . $tag->getText() . $tag->renderElements() . self::close($tag);
    }

    /**
     * Render open Tag
     *
     * @param TagInterface $tag
     * @param bool         $autoClosed
     *
     * @return string
     */
    private static function open(TagInterface $tag, $autoClosed = false)
    {
        $output =  '<' . $tag->getType();

        if ($tag->hasAttributes()) {
            $output .= ' ' . $tag->renderAttributes();
        }

        $output .= ($autoClosed ? '/>' : '>');

        return $output;
    }

    /**
     * Render close tag
     *
     * @param TagInterface $tag
     *
     * @return string
     */
    private static function close(TagInterface $tag)
    {
        return '</' . $tag->getType() . '>';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if it's an auto-closed Tag
     *
     * @param string $type
     *
     * @return bool
     */
    private static function isAutoClosed($type)
    {
        return in_array($type, self::$autoCloseTags);
    }
}

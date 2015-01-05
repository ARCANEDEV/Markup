<?php namespace Arcanedev\Markup;

use Arcanedev\Markup\Entities\Tag;

class Builder
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
    public static function make(Tag $tag)
    {
        if ($tag->isTextType()) {
            return $tag->getText();
        }

        return self::isAutoClosed($tag->getType())
            ? self::open($tag, true)
            : self::open($tag) . $tag->getText() . $tag->contentToString() . self::close($tag);
    }

    private static function open(Tag $tag, $autoClosed = false)
    {
        $output =  '<' . $tag->getType();

        if ($tag->hasAttributes()) {
            $output .= ' ' . $tag->renderAttributes();
        }

        $output .= ($autoClosed ? '/>' : '>');

        return $output;
    }

    private static function close(Tag $tag)
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

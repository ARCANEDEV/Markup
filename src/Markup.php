<?php namespace Arcanedev\Markup;

use Arcanedev\Markup\Entities\Tag;
use Arcanedev\Markup\Contracts\MarkupInterface;

class Markup implements MarkupInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new Tag
     *
     * @param string $type
     * @param array  $attributes
     *
     * @return Tag
     */
    private static function tag($type = '', $attributes = [])
    {
        return Tag::make($type, $attributes);
    }

    /**
     * Create a new Tag (Alias)
     *
     * @param string $type
     * @param array  $attributes
     *
     * @return Tag
     */
    public static function make($type = '', $attributes = [])
    {
        return self::tag($type, $attributes);
    }

    /* ------------------------------------------------------------------------------------------------
     |  HTML Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create IMG Tag
     *
     * @param string $src
     * @param string $alt
     * @param array  $attributes
     *
     * @return Tag
     */
    public static function img($src, $alt = '', array $attributes = [])
    {
        $attributes = array_merge([
            'src' => $src,
            'alt' => $alt
        ], $attributes);

        return self::tag('img', $attributes);
    }

    /**
     * Create META Tag
     *
     * @param string $name
     * @param string $value
     * @param string $content
     * @param array  $attributes
     *
     * @return Tag
     */
    public static function meta($name = 'name', $value, $content, array $attributes = [])
    {
        $attributes = array_merge([
            $name => $value,
            'content' => $content
        ], $attributes);

        return self::tag('meta', $attributes);
    }

    /**
     * Create ANCHOR Tag
     *
     * @param string $href
     * @param string $text
     * @param array  $attributes
     *
     * @return Tag
     */
    public static function link($href = '', $text = '', array $attributes = [])
    {
        $attributes = array_merge([
            'href'  => $href,
        ], $attributes);

        return self::tag('a', $attributes)->text($text);
    }
}

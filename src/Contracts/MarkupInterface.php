<?php namespace Arcanedev\Markup\Contracts;

use Arcanedev\Markup\Contracts\Entities\TagInterface;

interface MarkupInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new Tag (Alias)
     *
     * @param string $type
     * @param array  $attributes
     *
     * @return TagInterface
     */
    public static function make($type = '', $attributes = []);

    /* ------------------------------------------------------------------------------------------------
     |  HTML Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create TITLE
     *
     * @param string $text
     *
     * @return TagInterface
     */
    public static function title($text = '');

    /**
     * Create IMG Tag
     *
     * @param string $src
     * @param string $alt
     * @param array  $attributes
     *
     * @return TagInterface
     */
    public static function img($src, $alt = '', array $attributes = []);

    /**
     * Create META Tag
     *
     * @param string $name
     * @param string $value
     * @param string $content
     * @param array  $attributes
     *
     * @return TagInterface
     */
    public static function meta($name = 'name', $value, $content, array $attributes = []);

    /**
     * Create ANCHOR Tag
     *
     * @param string $href
     * @param string $text
     * @param array  $attributes
     *
     * @return TagInterface
     */
    public static function link($href = '', $text = '', array $attributes = []);
}

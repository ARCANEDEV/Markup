<?php namespace Arcanedev\Markup\Contracts;

use Arcanedev\Markup\Contracts\Entities\TagInterface;

interface BuilderInterface
{
    /**
     * Render a Tag and its elements
     *
     * @param TagInterface $tag
     *
     * @return string
     */
    public static function make(TagInterface $tag);
}
<?php namespace Arcanedev\Markup\Contracts\Entities\Tag;

use Arcanedev\Markup\Entities\Tag;

interface ElementCollectionInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Add Tag element to collection
     *
     * @param  Tag $tag
     *
     * @return ElementCollectionInterface
     */
    public function add(&$tag);

    /**
     * Get previous tag
     *
     * @param  Tag $tag
     *
     * @return Tag|null
     */
    public function getPrevious(Tag $tag);

    /**
     * Get Next Tag
     *
     * @param  Tag $tag
     *
     * @return Tag|null
     */
    public function getNext(Tag $tag);

    /**
     * Render tag elements
     *
     * @return string
     */
    public function render();

    /**
     * Remove tag element from collection
     *
     * @param  Tag $tag
     *
     * @return array
     */
    public function remove($tag);
}
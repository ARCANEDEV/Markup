<?php namespace Arcanedev\Markup\Entities\Tag;

use Arcanedev\Markup\Entities\Tag;
use Arcanedev\Markup\Support\Collection;

class ElementCollection extends Collection
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
     * @return ElementCollection
     */
    public function addElement(&$tag)
    {
        $this->push($tag);

        return $this;
    }

    /**
     * Get previous tag
     *
     * @param  Tag $tag
     *
     * @return Tag|null
     */
    public function getPrevious(Tag $tag)
    {
        return $this->getNextItem($tag, array_reverse($this->items));
    }

    /**
     * Get Next Tag
     *
     * @param  Tag $tag
     *
     * @return Tag|null
     */
    public function getNext(Tag $tag)
    {
        return $this->getNextItem($tag, $this->items);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get next item from items array
     *
     * @param  Tag   $item
     * @param  array $items
     *
     * @return Tag|null
     */
    private function getNextItem(Tag $item, $items)
    {
        $currentItem = $items[0];

        while ($currentItem !== null and $currentItem !== $item) {
            $currentItem = next($items);
        }

        $next = next($items);

        return $next !== false ? $next : null;
    }
}

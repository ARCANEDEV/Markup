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
     * Get Next Tag
     *
     * @param  Tag  $element
     * @param  bool $reversed
     *
     * @return Tag|null
     */
    public function getNext($element, $reversed = false)
    {
        reset($this->items);

        $items = $reversed
            ? array_reverse($this->items)
            : $this->items;

        $currentElt = $items[0];

        while ($currentElt !== null and $currentElt !== $element) {
            $currentElt = next($items);
        }

        $next = next($items);

        return $next !== false ? $next : null;
    }

    /**
     * Get previous tag
     *
     * @param  Tag $element
     *
     * @return Tag|null
     */
    public function getPrevious(Tag $element)
    {
        return $this->getNext($element, true);
    }
}

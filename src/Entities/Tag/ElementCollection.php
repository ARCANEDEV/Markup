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
    public function add(&$tag)
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

    /**
     * Render tag elements
     *
     * @return string
     */
    public function render()
    {
        $output = $this->each(function($tag) {
            /** @var Tag $tag */
            return $tag->render();
        });

        return implode('', $output->toArray());
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

    /**
     * Remove tag element from collection
     *
     * @param  Tag $tag
     *
     * @return array
     */
    public function remove($tag)
    {
        if ($this->count() == 0) {
            return [$this, null];
        }

        $deleted = null;

        foreach ($this->items as $key => $element) {
            if ($element === $tag) {
                $this->forget($key);

                $deleted = $tag;

                break;
            }
        }

        return [$this, $deleted];
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\ForEachGroup;

use ArrayIterator;
use IteratorAggregate;

final class GroupCollection implements IteratorAggregate
{
    /**
     * @var array|Group[]
     */
    private $items = [];

    /**
     * @param string $groupingKey
     * @return Group
     */
    public function get($groupingKey)
    {
        if (!isset($this->items[$groupingKey])) {
            $this->items[$groupingKey] = new Group($groupingKey);
        }
        return $this->items[$groupingKey];
    }

    /**
     * @return int
     */
    public function countGroupItems()
    {
        $count = 0;

        foreach ($this->items as $item) {
            $count += $item->count();
        }

        return $count;
    }

    /**
     * @return ArrayIterator|Group[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}

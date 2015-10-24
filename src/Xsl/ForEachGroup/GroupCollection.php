<?php
namespace Genkgo\Xsl\Xsl\ForEachGroup;

use ArrayIterator;
use IteratorAggregate;

/**
 * Class GroupCollection
 * @package Genkgo\Xsl\Xsl\ForEachGroup
 */
class GroupCollection implements IteratorAggregate
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
     * @return ArrayIterator|Group[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}

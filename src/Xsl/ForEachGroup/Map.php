<?php
namespace Genkgo\Xsl\Xsl\ForEachGroup;

/**
 * Class Map
 * @package Genkgo\Xsl\Xsl\ForEachGroup
 */
final class Map {

    /**
     * @var array|GroupCollection[]
     */
    private $groups = [];

    /**
     * @param $groupId
     * @return GroupCollection
     */
    public function get($groupId) {
        if (!isset($this->groups[$groupId])) {
            $this->groups[$groupId] = new GroupCollection();
        }

        return $this->groups[$groupId];
    }

}
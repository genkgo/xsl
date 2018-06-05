<?php
namespace Genkgo\Xsl\Xsl\ForEachGroup;

/**
 * Class Map
 * @package Genkgo\Xsl\Xsl\ForEachGroup
 */
final class Map
{
    /**
     * @var array|GroupCollection[]
     */
    private $groups = [];

    /**
     * @param $groupId
     * @param $iterationId
     * @return GroupCollection
     */
    public function get($groupId, $iterationId)
    {
        if (!isset($this->groups[$groupId][$iterationId])) {
            throw new \InvalidArgumentException('Cannot find group and/or iteration');
        }

        return $this->groups[$groupId][$iterationId];
    }

    /**
     * @param $groupId
     * @return int
     */
    public function newIterationId($groupId)
    {
        if (!isset($this->groups[$groupId])) {
            $this->groups[$groupId] = [new GroupCollection()];
            return 0;
        }

        $id = count($this->groups[$groupId]);
        $this->groups[$groupId][$id] = new GroupCollection();
        return $id;
    }
}

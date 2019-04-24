<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\ForEachGroup;

final class Map
{
    /**
     * @var array|GroupCollection[][]
     */
    private $groups = [];

    /**
     * @param string $groupId
     * @param string $iterationId
     * @return GroupCollection
     */
    public function get(string $groupId, string $iterationId): GroupCollection
    {
        if (!isset($this->groups[$groupId][$iterationId])) {
            throw new \InvalidArgumentException('Cannot find group and/or iteration');
        }

        return $this->groups[$groupId][$iterationId];
    }

    /**
     * @param string $groupId
     * @return int
     */
    public function newIterationId(string $groupId): int
    {
        if (!isset($this->groups[$groupId])) {
            $this->groups[$groupId] = [new GroupCollection()];
            return 0;
        }

        $id = \count($this->groups[$groupId]);
        $this->groups[$groupId][$id] = new GroupCollection();
        return $id;
    }
}

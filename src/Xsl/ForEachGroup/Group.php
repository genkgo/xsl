<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\ForEachGroup;

final class Group implements \Countable
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $ids = [];

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param string $id
     */
    public function addId(string $id): void
    {
        $this->ids[] = $id;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->ids);
    }
}

<?php
namespace Genkgo\Xsl\Xsl\ForEachGroup;

/**
 * Class Group
 * @package Genkgo\Xsl\Xsl\ForEachGroup
 */
class Group implements \Countable
{
    /**
     * @var
     */
    private $key;
    /**
     * @var array
     */
    private $ids = [];

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param string $id
     */
    public function addId($id)
    {
        $this->ids[] = $id;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->ids);
    }
}

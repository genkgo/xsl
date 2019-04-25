<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Callback;

final class FunctionCollection
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * @param string $namespace
     * @param FunctionMapInterface $functionMap
     */
    public function attach(string $namespace, FunctionMapInterface $functionMap): void
    {
        $this->collection[$namespace] = $functionMap;
    }

    /**
     * @param string $namespace
     * @return FunctionMapInterface
     */
    public function get(string $namespace): FunctionMapInterface
    {
        if (!isset($this->collection[$namespace])) {
            throw new \InvalidArgumentException('No functions registered for namespace ' . $namespace);
        }

        return $this->collection[$namespace];
    }
}

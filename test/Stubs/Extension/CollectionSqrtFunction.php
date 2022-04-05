<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\TransformationContext;

final class CollectionSqrtFunction
{
    /**
     * @var array<int, int>
     */
    private $collection;

    /**
     * @param array<int, int> $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param Arguments $arguments
     * @return mixed
     */
    public function sqrt(Arguments $arguments)
    {
        $unpacked = $arguments->unpack();
        return \count($unpacked) * \count($this->collection);
    }
}

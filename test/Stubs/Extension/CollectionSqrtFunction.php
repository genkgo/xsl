<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\TransformationContext;

final class CollectionSqrtFunction
{
    /**
     * @var array
     */
    private $collection;

    /**
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function sqrt(array $arguments, TransformationContext $context)
    {
        return \count($arguments) * \count($this->collection);
    }
}

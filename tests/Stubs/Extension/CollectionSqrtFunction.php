<?php
namespace Genkgo\Xsl\Stubs\Extension;

use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;

class CollectionSqrtFunction implements MethodCallInterface
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return mixed
     */
    public function call($arguments, TransformationContext $context)
    {
        return count($arguments) * count($this->collection);
    }
}

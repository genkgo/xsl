<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DOMDocument;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

class GroupIterationId implements FunctionInterface, MethodCallInterface
{
    private $groups;

    public function __construct(ForEachGroupMap $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set(XslTransformations::URI . ':group-iteration-id', $this);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return int
     */
    public function call($arguments, TransformationContext $context)
    {
        return $this->groups->newIterationId($arguments[0]);
    }
}

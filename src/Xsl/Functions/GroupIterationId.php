<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

final class GroupIterationId implements FunctionInterface, MethodCallInterface
{
    /**
     * @var ForEachGroupMap
     */
    private $groups;

    /**
     * @param ForEachGroupMap $groups
     */
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
     * @param array $arguments
     * @param TransformationContext $context
     * @return int
     */
    public function call(array $arguments, TransformationContext $context)
    {
        if (!isset($arguments[0])) {
            throw new \UnexpectedValueException('Got no arguments, expected 1');
        }

        return $this->groups->newIterationId($arguments[0]);
    }
}

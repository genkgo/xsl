<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMNode;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;

final class GroupIterationId implements FunctionInterface
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
     * @param Arguments $arguments
     * @param TransformationContext $context
     * @return int
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        return $this->groups->newIterationId($arguments->get(0));
    }

    /**
     * @param Lexer $lexer
     * @param DOMNode $currentElement
     * @return array
     */
    public function serialize(Lexer $lexer, DOMNode $currentElement): array
    {
        return [$lexer->current()];
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMNode;
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

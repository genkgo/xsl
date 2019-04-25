<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMDocument;
use DOMNode;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xsl\ForEachGroup\Group;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

final class GroupIterate implements FunctionInterface
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
     * @return DOMDocument
     */
    public function call(Arguments $arguments, TransformationContext $context)
    {
        /** @var Group[] $group */
        $group = $this->groups->get($arguments->get(0), $arguments->get(1));

        $document = new DOMDocument();
        $groupElement = $document->createElementNS(XslTransformations::URI, 'xsl:groups');
        $document->appendChild($groupElement);

        foreach ($group as $item) {
            $itemElement = $document->createElementNS(XslTransformations::URI, 'xsl:group');
            $itemElement->setAttribute('key', $item->getKey());

            $ids = $item->getIds();
            foreach ($ids as $id) {
                $itemElement->appendChild($document->createElementNS(XslTransformations::URI, 'xsl:element-id', $id));
            }

            $groupElement->appendChild($itemElement);
        }

        return $document;
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

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Functions;

use DOMDocument;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xsl\ForEachGroup\Group;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

final class GroupIterate implements FunctionInterface, MethodCallInterface
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
        $functionMap->set(XslTransformations::URI . ':group-iterate', $this);
    }

    /**
     * @param array $arguments
     * @param TransformationContext $context
     * @return DOMDocument
     */
    public function call(array $arguments, TransformationContext $context)
    {
        /** @var Group[] $group */
        $group = $this->groups->get($arguments[0], $arguments[1]);

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
}

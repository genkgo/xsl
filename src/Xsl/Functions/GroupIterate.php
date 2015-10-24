<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DOMDocument;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xsl\ForEachGroup\Group;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

class GroupIterate implements FunctionInterface, MethodCallInterface
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
        $functionMap->set(XslTransformations::URI . ':group-iterate', $this);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return DOMDocument
     */
    public function call($arguments, TransformationContext $context)
    {
        /** @var Group[] $group */
        $group = $this->groups->get($arguments[0]);

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

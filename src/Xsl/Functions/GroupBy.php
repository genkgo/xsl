<?php
namespace Genkgo\Xsl\Xsl\Functions;

use DOMElement;
use DOMXPath;
use Genkgo\Xsl\Callback\FunctionInterface;
use Genkgo\Xsl\Callback\MethodCallInterface;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Util\FunctionMap;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xsl\ForEachGroup\Map as ForEachGroupMap;
use Genkgo\Xsl\Xsl\XslTransformations;

class GroupBy implements FunctionInterface, MethodCallInterface
{
    private $compiler;
    private $groups;

    public function __construct(Compiler $compiler, ForEachGroupMap $groups)
    {
        $this->compiler = $compiler;
        $this->groups = $groups;
    }


    /**
     * @param FunctionMap $functionMap
     * @return void
     */
    public function register(FunctionMap $functionMap)
    {
        $functionMap->set(XslTransformations::URI . ':group-by', $this);
    }

    /**
     * @param $arguments
     * @param TransformationContext $context
     * @return true
     */
    public function call($arguments, TransformationContext $context)
    {
        /** @var string $groupId */
        $groupId = $arguments[0];
        /** @var DOMElement[] $elements */
        $elements = $arguments[1];
        /** @var string $elementId */
        $elementId = $arguments[2];
        /** @var string $groupBy */
        $groupBy = base64_decode($arguments[3]);

        $collection = $this->groups->get($groupId);
        $namespaces = FetchNamespacesFromDocument::fetch($elements[0]->ownerDocument);

        foreach ($elements as $key => $element) {
            $xpath = new DOMXPath($element->ownerDocument);
            $xpath->registerPhpFunctions($context->getPhpFunctions());

            $groupingKey = $xpath->evaluate(
                $this->compiler->compile('string(' . $groupBy . ')', $namespaces),
                $element
            );

            $collection->get($groupingKey)->addId($elementId);
        }

        return true;
    }
}

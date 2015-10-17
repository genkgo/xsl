<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Schema\XmlSchema;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

class ElementValueOf implements ElementTransformerInterface {

    public function transform(DOMElement $element, DocumentContext $context)
    {
        if ($element->nodeName === 'xsl:value-of' && $element->hasAttribute('separator')) {
            $select = $element->getAttribute('select');
            $separator = $element->getAttribute('separator');

            $endsWith = '/xs:sequence';
            if (substr($select, strlen($endsWith) * -1) === $endsWith) {
                $callback = (new FunctionBuilder('php:function'))
                    ->addArgument(PhpCallback::class . '::call')
                    ->addArgument(static::class)
                    ->addArgument('valueOfSeparate')
                    ->addArgument($select, false)
                    ->addArgument($separator);

                $element->setAttribute('select', $callback->build());
            }

            $element->removeAttribute('separator');
        }
    }

    /**
     * @param DOMElement[] $elements
     * @param $separator
     * @return string
     */
    public static function valueOfSeparate($elements, $separator)
    {
        $result = '';

        $index = 0;
        foreach ($elements as $sequence) {
            $itemsXpath = new \DOMXPath($sequence->ownerDocument);
            $items = $itemsXpath->query('xs:*', $sequence);

            foreach ($items as $node) {
                if ($index > 0) {
                    $result .= $separator;
                }

                $result .= $node->nodeValue;
                $index++;
            }
        }

        return $result;
    }
}
<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMDocument;
use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\TransformationContext;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

/**
 * Class ElementValueOf
 * @package Genkgo\Xsl\Xsl\Node
 */
class ElementValueOf implements ElementTransformerInterface
{

    /**
     * @param DOMDocument $document
     * @return bool
     */
    public function supports(DOMDocument $document)
    {
        return $document->documentElement->getAttribute('version') !== '1.0';
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element)
    {
        if ($element->nodeName === 'xsl:value-of') {
            $select = $element->getAttribute('select');
            $separator = $element->hasAttribute('separator') ? $element->getAttribute('separator') : ' ';

            $callback = (new FunctionBuilder('php:function'))
                ->addArgument(PhpCallback::class . '::callStatic')
                ->addArgument(static::class)
                ->addArgument('valueOf')
                ->addExpression($select)
                ->addArgument($separator);

            $element->setAttribute('select', $callback->build());
            $element->removeAttribute('separator');
        }
    }

    /**
     * @param mixed $elements
     * @param $separator
     * @return string
     */
    public static function valueOf($elements, $separator)
    {
        if (is_scalar($elements)) {
            return $elements;
        }

        $result = '';

        if (is_array($elements)) {
            $index = 0;
            foreach ($elements as $element) {
                if ($index > 0) {
                    $result .= $separator;
                }

                $result .= $element->nodeValue;
                $index++;
            }
        }


        return $result;
    }
}

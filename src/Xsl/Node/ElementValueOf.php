<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

/**
 * Class ElementValueOf
 * @package Genkgo\Xsl\Xsl\Node
 */
class ElementValueOf implements ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @param DocumentContext $context
     * @return void
     */
    public function transform(DOMElement $element, DocumentContext $context)
    {
        if ($element->nodeName === 'xsl:value-of') {
            $select = $element->getAttribute('select');
            $separator = $element->hasAttribute('separator') ? $element->getAttribute('separator') : ' ';

            $callback = (new FunctionBuilder('php:function'))
                ->addArgument(PhpCallback::class . '::call')
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
        $result = '';

        if (is_scalar($elements)) {
            return $elements;
        }

        $index = 0;
        foreach ($elements as $element) {
            if ($index > 0) {
                $result .= $separator;
            }

            $result .= $element->nodeValue;
            $index++;
        }

        return $result;
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\Callback\Arguments;
use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

final class ElementValueOf implements ElementTransformerInterface
{
    /**
     * @param DOMElement $element
     * @return bool
     */
    public function supports(DOMElement $element): bool
    {
        return $element->ownerDocument->documentElement->getAttribute('version') !== '1.0' && $element->localName === 'value-of';
    }

    /**
     * @param DOMElement $element
     */
    public function transform(DOMElement $element): void
    {
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

    /**
     * @param Arguments $arguments
     * @return string|int|float|bool
     */
    public static function valueOf(Arguments $arguments)
    {
        $elements = $arguments->get(0);
        try {
            $separator = (string)$arguments->castFromSchemaType(1);
        } catch (\InvalidArgumentException $e) {
            $separator = ' ';
        }

        if (\is_scalar($elements)) {
            return $elements;
        }

        $result = '';

        if (\is_array($elements)) {
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

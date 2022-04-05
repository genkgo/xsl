<?php
declare(strict_types=1);


namespace Genkgo\Xsl\Xsl;

use Genkgo\Xsl\Callback\PhpCallback;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\FunctionBuilder;
use Genkgo\Xsl\Xsl\Node\ElementValueOf;
use RuntimeException;

final class TextValueTemplate
{
    /**
     * @var Compiler
     */
    private $xpathCompiler;

    /**
     * @param Compiler $compiler
     */
    public function __construct(Compiler $compiler)
    {
        $this->xpathCompiler = $compiler;
    }

    /**
     * @param \DOMText $textNode
     */
    public function expand(\DOMText $textNode)
    {
        $expression = $textNode->nodeValue;
        $textNode->nodeValue = '';
        $parentNode = $textNode->parentNode;
        $components = [];

        $length = \strlen($expression);
        $last = 0;
        while ($last < $length) {
            $i0 = \strpos($expression, "{", $last);
            $i1 = \strpos($expression, "{{", $last);
            $i8 = \strpos($expression, "}", $last);
            $i9 = \strpos($expression, "}}", $last);

            if (($i0 === false || $length < $i0) && ($i8 === false || $length < $i8)) {   // found end of string
                $components[] = \substr($expression, $last);
                break;
            } elseif ($i8 !== false && ($i0 === false || $i8 < $i0)) {             // found a "}"
                if ($i8 !== $i9) {                        // a "}" that isn't a "}}"
                    throw new \InvalidArgumentException("Closing curly brace in attribute value template \"" . $expression . "\" must be doubled", 370);
                }
                $components[] = \substr($expression, $last, $i8 + 2 - $last);
                $last = $i8 + 2;
            } elseif ($i1 !== false && $i1 === $i0) {              // found a doubled "{{"
                $components[] = \substr($expression, $last, $i1 + 2 - $last);
                $last = $i1 + 2;
            } elseif ($i0 !== false) {                        // found a single "{"
                if ($i0 > $last) {
                    $components[] = \substr($expression, $last, $i0 - $last);
                }

                if ($i8 === false) {
                    throw new \InvalidArgumentException("Curly brace in attribute value template \"" . $expression . "\" must be closed", 370);
                }

                $compileFrom = $i0 + 1;
                $compileUntil = $i8 - $compileFrom;

                $this->appendTextNode($textNode, $components);

                $callback = (new FunctionBuilder('php:function'))
                    ->addArgument(PhpCallback::class . '::callStatic')
                    ->addArgument(ElementValueOf::class)
                    ->addArgument('valueOf')
                    ->addExpression(\substr($expression, $compileFrom, $compileUntil))
                    ->addArgument(' ');

                $valueOf = $textNode->ownerDocument->createElementNS(
                    XslTransformations::URI,
                    'value-of'
                );

                $valueOf->setAttribute(
                    'select',
                    $this->xpathCompiler->compile($callback->build(), $textNode)
                );

                $parentNode->appendChild($valueOf);

                $components = [];
                $last = $i8 + 1;
            } else {
                // @codeCoverageIgnoreStart
                throw new RuntimeException("Internal error parsing Attribute Value Template");
                // @codeCoverageIgnoreEnd
            }
        }

        $this->appendTextNode($textNode, $components);
        $parentNode->removeChild($textNode);
    }

    /**
     * @param \DOMText $textNode
     * @param array $components
     */
    private function appendTextNode(\DOMText $textNode, array $components)
    {
        if (empty($components)) {
            return;
        }

        $textNode->parentNode->appendChild(
            $textNode->ownerDocument->createTextNode(\implode('', $components))
        );
    }
}

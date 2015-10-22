<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMAttr;
use Genkgo\Xsl\Util\FetchNamespacesFromDocument;
use Genkgo\Xsl\Xpath\Compiler;
use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;
use Genkgo\Xsl\Xsl\AttributeTransformerInterface;
use RuntimeException;

/**
 * Class AttributeValueTemplates
 * @package Genkgo\Xsl\Xsl\Element
 */
final class AttributeValueTemplates implements AttributeTransformerInterface
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
     * @param DOMAttr $attribute
     * @return bool
     */
    public function supports(DOMAttr $attribute)
    {
        return true;
    }

    /**
     * @param DOMAttr $attribute
     * @throws InvalidArgumentException
     * @credits https://github.com/Saxonica/Saxon-CE/ https://github.com/Saxonica/Saxon-CE/blob/master/notices/MOZILLA.txt
     */
    public function transform(DOMAttr $attribute)
    {
        $namespaces = FetchNamespacesFromDocument::fetch($attribute->ownerDocument);
        $expression = $attribute->nodeValue;
        $components = [];

        $length = strlen($expression);
        $last = 0;
        while ($last < $length) {
            $i0 = strpos($expression, "{", $last);
            $i1 = strpos($expression, "{{", $last);
            $i8 = strpos($expression, "}", $last);
            $i9 = strpos($expression, "}}", $last);

            if (($i0 === false || $length < $i0) && ($i8 === false || $length < $i8)) {   // found end of string
                $components[] = substr($expression, $last);
                break;
            } elseif ($i8 >= 0 && ($i0 === false || $i8 < $i0)) {             // found a "}"
                if ($i8 !== $i9) {                        // a "}" that isn't a "}}"
                    $exception = new InvalidArgumentException("Closing curly brace in attribute value template \"" . $expression . "\" must be doubled");
                    $exception->setErrorCode("XTSE0370");
                    throw $exception;
                }
                $components[] = substr($expression, $last, $i8 + 2 - $last);
                $last = $i8 + 2;
            } elseif ($i1 >= 0 && $i1 === $i0) {              // found a doubled "{{"
                $components[] = substr($expression, $last, $i1 + 2 - $last);
                $last = $i1 + 2;
            } elseif ($i0 >= 0) {                        // found a single "{"
                if ($i0 > $last) {
                    $components[] = substr($expression, $last, $i0 - $last);
                }

                if ($i8 === false) {
                    $exception = new InvalidArgumentException("Curly brace in attribute value template \"" . $expression . "\" must be closed");
                    $exception->setErrorCode("XTSE0370");
                    throw $exception;
                }

                $compileFrom = $i0 + 1;
                $compileUntil = $i8 - $compileFrom;

                $components[] = '{';
                $components[] = $this->xpathCompiler->compile(substr($expression, $compileFrom, $compileUntil), $namespaces);
                $components[] = '}';
                $last = $i8 + 1;
            } else {
                throw new RuntimeException("Internal error parsing Attribute Value Template");
            }
        }

        $attribute->nodeValue = implode('', $components);
    }
}

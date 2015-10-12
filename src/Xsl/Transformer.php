<?php
namespace Genkgo\Xsl\Xsl;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\TransformerInterface;
use Genkgo\Xsl\Transpiler;
use Genkgo\Xsl\Xpath\Lexer;

/**
 * Class Transformer
 * @package Genkgo\Xsl\Xsl
 */
class Transformer implements TransformerInterface
{
    /**
     * @param DOMDocument $document
     * @param Transpiler $transpiler
     */
    public function transform(DOMDocument $document, Transpiler $transpiler)
    {
        $document->documentElement->setAttribute('xmlns:php', 'http://php.net/xsl');
        $matchAndSelectElements = new DOMXPath($document);

        /** @var DOMNodeList|DOMElement[] $list */
        $list = $matchAndSelectElements->query('//xsl:*[@match|@select]');
        foreach ($list as $element) {
            if ($element->hasAttribute('match')) {
                $element->setAttribute(
                    'match',
                    $this->transformXpathExpression(
                        $element->getAttribute('match'),
                        $transpiler
                    )
                );
            }

            if ($element->hasAttribute('select')) {
                $element->setAttribute(
                    'select',
                    $this->transformXpathExpression(
                        $element->getAttribute('select'),
                        $transpiler
                    )
                );
            }

            if ($element->nodeName === 'xsl:value-of' && $element->hasAttribute('separator')) {
                $select = $element->getAttribute('select');
                $separator = $element->getAttribute('separator');
                $callback = static::class . '::valueOfSeparate';

                $element->setAttribute(
                    'select',
                    'php:function(\'' . $callback . '\', ' . $select . ', \'' . $separator . '\')'
                );
            }
        }
    }

    /**
     * @param $xpathExpression
     * @param Transpiler $transpiler
     * @return string
     */
    private function transformXpathExpression($xpathExpression, Transpiler $transpiler)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            if ($transpiler->hasFunction($token)) {
                $function = $transpiler->getFunction($token);
                $resultTokens = array_merge($resultTokens, $function->replace($lexer));
            } else {
                $resultTokens[] = $token;
            }
        }

        return implode('', $resultTokens);
    }

    /**
     * @param DOMDocument[] $elements
     * @param $separator
     * @return string
     */
    public static function valueOfSeparate ($elements, $separator) {
        $result = '';

        $index = 0;
        foreach ($elements as $element) {
            foreach ($element->documentElement->childNodes as $node) {
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

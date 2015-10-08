<?php
namespace Genkgo\Xsl\Xpath;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\TransformerInterface;

class Transformer implements TransformerInterface {

    public function transform(DOMDocument $document)
    {
        $document->documentElement->setAttribute('xmlns:php', 'http://php.net/xsl');
        $matchAndSelectElements = new DOMXPath($document);

        /** @var DOMNodeList|DOMElement[] $list */
        $list = $matchAndSelectElements->query('//xsl:*[@match|@select]');
        foreach ($list as $element) {
            if ($element->hasAttribute('match')) {
                $element->setAttribute('match', $this->transformXpathExpression($element->getAttribute('match')));
            }

            if ($element->hasAttribute('select')) {
                $element->setAttribute('select', $this->transformXpathExpression($element->getAttribute('select')));
            }
        }
    }

    private function transformXpathExpression($xpathExpression)
    {
        $resultTokens = [];
        $lexer = Lexer::tokenize($xpathExpression);
        foreach ($lexer as $token) {
            if ($token === 'abs') {
                $resultTokens[] = 'php:function';
                $resultTokens[] = '(';
                $resultTokens[] = '\'';
                $resultTokens[] = Functions::class. '::abs';
                $resultTokens[] = '\'';
                $resultTokens[] = ',';
                $lexer->next();
            } else {
                $resultTokens[] = $token;
            }
        }

        return implode('', $resultTokens);
    }
}
<?php
namespace Genkgo\Xsl\Xpath;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use Genkgo\Xsl\TransformerInterface;

class Transformer implements TransformerInterface {

    private $functions = [];

    public function __construct () {
        $this->addFunction('abs');
        $this->addFunction('ceiling');
        $this->addFunction('floor');
        $this->addFunction('round');
        $this->addFunction('roundHalfToEven');
        $this->addFunction('endsWith');
        $this->addFunction('indexOf');
    }

    private function addFunction ($name, $class = Functions::class) {
        $this->functions[$this->dasherize($name)] = [$class, $name];
    }

    private function dasherize ($methodName) {
        if (ctype_lower($methodName) === false) {
            $methodName = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.  '-', $methodName));
            $methodName = preg_replace('/\s+/', '', $methodName);
        }

        return $methodName;
    }

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
            if (isset($this->functions[$token])) {
                list($class, $method) = $this->functions[$token];
                $resultTokens[] = 'php:function';
                $resultTokens[] = '(';
                $resultTokens[] = '\'';
                $resultTokens[] = $class. '::' . $method;
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
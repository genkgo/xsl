<?php
namespace Genkgo\Xsl\Xsl\Node;

use DOMElement;
use Genkgo\Xsl\DocumentContext;
use Genkgo\Xsl\Xsl\ElementTransformerInterface;

class ElementIteration implements ElementTransformerInterface {

    private static $iterations = ['xsl:for-each', 'xsl:for-each-group', 'xsl:apply-templates'];

    public function transform(DOMElement $element, DocumentContext $context)
    {
        if (in_array($element->nodeName, self::$iterations)) {
            $select = $element->getAttribute('select');
            $endsWith = '/xs:sequence';

            if (substr($select, strlen($endsWith) * -1) === $endsWith) {
                $element->setAttribute('select', $select . '/*');
            }
        }
    }
}
<?php
namespace Genkgo\Xsl\Unit;

use DOMDocument;
use DOMXPath;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Xsl\Node\IncludeWindowsTransformer;

class IncludeWindowsTransformerTest extends AbstractTestCase
{
    public function testFullPath()
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->load('Stubs/include-full-windows-path.xsl');

        $transformer = new IncludeWindowsTransformer();
        $xpath = new DOMXPath($document);

        $items = 0;

        $list = $xpath->query('//xsl:include');
        /** @var \DOMElement $element */
        foreach ($list as $element) {
            $transformer->transform($element);
            $this->assertEquals(
                Stream::PROTOCOL . Stream::HOST . '/C:/test/include2.xsl',
                $element->getAttribute('href')
            );
            $items++;
        }

        $this->assertEquals(1, $items);
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use DOMDocument;
use DOMXPath;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Stream;
use Genkgo\Xsl\Xsl\Node\IncludeWindowsTransformer;

class IncludeWindowsTransformerTest extends AbstractTestCase
{
    public function testFullPath(): void
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->load('Stubs/include-full-windows-path.xsl');

        $transformer = new IncludeWindowsTransformer();
        $xpath = new DOMXPath($document);

        $items = 0;

        /** @var \DOMNodeList<\DOMElement> $list */
        $list = $xpath->query('//xsl:include');
        foreach ($list as $element) {
            $this->assertTrue($transformer->supports($element));
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

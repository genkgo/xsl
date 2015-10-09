<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\XsltProcessor;

class ProcessXslt2DocumentsTest extends AbstractIntegrationTestCase
{
    public function testMultipleMethod()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/combine-multiple-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = new XsltProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals(157, trim($processorResult));
    }

}

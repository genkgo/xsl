<?php
namespace Genkgo\Xsl\Unit;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

class XsltProcessorTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $decorator = new XsltProcessor();

        $this->assertTrue($decorator instanceof \XSLTProcessor);
    }

    public function testWithConfig()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new Config());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion1()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-1.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new Config());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion2()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-2.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new Config());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion3()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-3.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new Config());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, strlen($decorator->transformToXML($xmlDoc)));
    }
}

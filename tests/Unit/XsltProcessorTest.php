<?php
namespace Genkgo\Xsl;

use DOMDocument;

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

    public function testXsl1DocumentAndDisableUpgrade()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor((new Config())->setUpgradeToXsl2(false));
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, strlen($decorator->transformToXML($xmlDoc)));
    }
}

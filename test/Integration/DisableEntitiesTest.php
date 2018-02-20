<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

final class DisableEntitiesTest extends AbstractIntegrationTestCase
{
    public static function tearDownAfterClass()
    {
        libxml_disable_entity_loader(false);
    }

    public function testDisableEntitiesWhenDocumentAlreadyLoaded()
    {
        $this->expectException(\DOMException::class);

        file_put_contents(sys_get_temp_dir() . '/xsl-passwd', 'test');
        $config = new Config();

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/entities.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        libxml_disable_entity_loader(true);

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processor->transformToXML($xmlDoc);
    }

    public function testDisableEntitiesInInclude()
    {
        $this->expectException(\DOMException::class);

        file_put_contents(sys_get_temp_dir() . '/xsl-passwd', 'test');
        $config = new Config();

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/entities-include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        libxml_disable_entity_loader(true);

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processor->transformToXML($xmlDoc);
    }

    public function testEntitiesEnabled()
    {
        libxml_disable_entity_loader(false);

        file_put_contents(sys_get_temp_dir() . '/xsl-passwd', 'test');
        $config = new Config();

        $xslDoc = new DOMDocument();
        $xslDoc->substituteEntities = true;
        $xslDoc->load('Stubs/entities.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);

        $this->assertEquals(
            base64_encode('test'),
            trim($processor->transformToXML($xmlDoc))
        );
    }

    public function testEntitiesEnabledInclude()
    {
        libxml_disable_entity_loader(false);

        file_put_contents(sys_get_temp_dir() . '/xsl-passwd', 'test');
        $config = new Config();

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/entities-include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $this->assertEquals(
            base64_encode('test'),
            trim($processor->transformToXML($xmlDoc))
        );
    }
}
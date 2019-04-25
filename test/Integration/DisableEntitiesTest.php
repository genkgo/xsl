<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\ProcessorFactory;
use Genkgo\Xsl\XsltProcessor;

final class DisableEntitiesTest extends AbstractIntegrationTestCase
{
    public function testDisableEntitiesWhenDocumentAlreadyLoaded()
    {
        $this->expectException(\DOMException::class);

        \file_put_contents(\sys_get_temp_dir() . '/xsl-passwd', 'test');
        $factory = new ProcessorFactory(new NullCache());

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/entities.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processor->transformToXML($xmlDoc);
    }

    public function testDisableEntitiesInInclude()
    {
        $this->expectException(\DOMException::class);

        \file_put_contents(\sys_get_temp_dir() . '/xsl-passwd', 'test');
        $factory = new ProcessorFactory(new NullCache());

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/entities-include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processor->transformToXML($xmlDoc);
    }
}

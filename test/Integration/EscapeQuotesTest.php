<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\ProcessorFactory;

final class EscapeQuotesTest extends AbstractIntegrationTestCase
{
    public function testDisableEntitiesWhenDocumentAlreadyLoaded(): void
    {
        $factory = new ProcessorFactory(new NullCache());

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/escape-quotes.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $this->assertEquals("'string1'string2'", \trim($processor->transformToXML($xmlDoc)));
    }
}

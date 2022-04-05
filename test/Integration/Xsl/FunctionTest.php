<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

final class FunctionTest extends AbstractIntegrationTestCase
{
    public function testFunction(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/Function/function.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/collection.xml');

        $this->assertEquals('4', \trim($processor->transformToXml($data)));
    }
}

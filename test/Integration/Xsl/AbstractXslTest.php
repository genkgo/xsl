<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractXslTest extends AbstractIntegrationTestCase
{
    /**
     * @param array<string, scalar> $parameters
     */
    protected function transformFile(string $path, array $parameters = []): string
    {
        $styleSheet = new DOMDocument();
        $styleSheet->preserveWhiteSpace = true;
        $styleSheet->load($path);

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);
        $processor->setParameter('', $parameters);

        $document = new DOMDocument();
        $document->load('Stubs/collection.xml');

        return \trim((string)$processor->transformToXml($document));
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractXpathTest extends AbstractIntegrationTestCase
{
    protected function transformFile($path, array $parameters = [])
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load($path);

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $processor->setParameter('', $parameters);

        $document = new DOMDocument();
        $document->load('Stubs/collection.xml');

        return \trim((string)$processor->transformToXml($document));
    }
}

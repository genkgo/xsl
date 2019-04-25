<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractXslTest extends AbstractIntegrationTestCase
{
    protected function transformFile($path, array $parameters = [])
    {
        $styleSheet = new DOMDocument();
        $styleSheet->preserveWhiteSpace = true;
        $styleSheet->load($path);

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        foreach ($parameters as $key => $value) {
            $processor->setParameter('', $key, $value);
        }

        $document = new DOMDocument();
        $document->load('Stubs/collection.xml');

        return \trim((string)$processor->transformToXml($document));
    }
}

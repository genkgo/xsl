<?php 
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Schema;

use DOMDocument;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractSchemaTest extends AbstractIntegrationTestCase
{
    protected function transformFile($path, array $parameters = [])
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load($path);

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        foreach ($parameters as $key => $value) {
            $processor->setParameter('', $key, $value);
        }

        $document = new DOMDocument();
        $document->load('Stubs/collection.xml');

        return \trim($processor->transformToXml($document));
    }
}

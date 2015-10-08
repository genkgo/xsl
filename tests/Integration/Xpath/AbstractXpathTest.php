<?php
namespace Genkgo\Xsl\Integration\Xpath;

use DOMDocument;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractXpathTest extends AbstractIntegrationTestCase {

    protected function transformFile ($path) {
        $styleSheet = new DOMDocument();
        $styleSheet->load($path);

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);
        return trim($processor->transformToXml($styleSheet));
    }

}
<?php
namespace Genkgo\Xsl\Integration\Xpath;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\XsltProcessor;

abstract class AbstractXpathTest extends AbstractTestCase {

    protected function transformFile ($path) {
        $styleSheet = new DOMDocument();
        $styleSheet->load($path);

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);
        return trim($processor->transformToXml($styleSheet));
    }

}
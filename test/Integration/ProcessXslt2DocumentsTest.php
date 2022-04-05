<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\XsltProcessor;

final class ProcessXslt2DocumentsTest extends AbstractIntegrationTestCase
{
    public function testMultipleMethod(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/combine-multiple-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals(157, \trim($processorResult));
    }

    public function testInclude(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/include2.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals(157, \trim($transpilerResult));
    }

    public function testIncludeFullPath(): void
    {
        $xslDoc = new DOMDocument("1.0", "UTF-8");
        $xslRoot = $xslDoc->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:stylesheet');
        $xslRoot->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:php', 'http://php.net/xsl');
        $xslRoot->setAttribute('exclude-result-prefixes', 'php');
        $xslRoot->setAttribute('version', '1.0');
        $xslDoc->appendChild($xslRoot);

        $include = $xslDoc->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:include');
        $include->setAttribute('href', \getcwd() . '/Stubs/include3.xsl');
        $xslRoot->appendChild($include);

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals(157, \trim($transpilerResult));
    }

    public function testExcludePrefixesAll(): void
    {
        $xslDoc = new DOMDocument("1.0", "UTF-8");
        $xslRoot = $xslDoc->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:stylesheet');
        $xslRoot->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:php', 'http://php.net/xsl');
        $xslRoot->setAttribute('exclude-result-prefixes', '#all');
        $xslRoot->setAttribute('version', '1.0');
        $xslDoc->appendChild($xslRoot);

        $include = $xslDoc->createElementNS('http://www.w3.org/1999/XSL/Transform', 'xsl:include');
        $include->setAttribute('href', \getcwd() . '/Stubs/include3.xsl');
        $xslRoot->appendChild($include);

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals(157, \trim($transpilerResult));
    }
}

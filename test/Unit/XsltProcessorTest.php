<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\XsltProcessor;
use PHPUnit\Framework\Error\Error;

class XsltProcessorTest extends AbstractTestCase
{
    public function testConstruct(): void
    {
        $decorator = new XsltProcessor(new NullCache());

        $this->assertTrue($decorator instanceof \XSLTProcessor);
    }

    public function testWithConfig(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new NullCache());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, \strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion1(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-1.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new NullCache());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, \strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion2(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-2.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new NullCache());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, \strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testVersion3(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/version-3.0.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $decorator = new XsltProcessor(new NullCache());
        $decorator->importStyleSheet($xslDoc);

        $this->assertGreaterThan(1, \strlen($decorator->transformToXML($xmlDoc)));
    }

    public function testExcludePrefixes(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/exclude-prefixes.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = new XsltProcessor(new NullCache());
        $processor->excludeAllPrefixes();
        $processor->importStyleSheet($xslDoc);

        $this->assertSame('<p>test</p>', \trim($processor->transformToXML($xmlDoc)));
    }

    public function testErrorHandlerIsRestoredAfterTransformToXml(): void
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->loadXML('<root/>');

        $xslDoc = new DOMDocument();
        $xslDoc->loadXML(
            '<?xml version="1.0"?>' . PHP_EOL
            . '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"/>'
        );

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($xslDoc);

        $processor->transformToXML($xmlDoc);

        // test that after transformation the error is captured by the
        // previous error handler and not by the one set in transformToXML
        $this->expectError();
        $this->expectErrorMessage('Error captured by phpunit error handler');
        \trigger_error('Error captured by phpunit error handler', E_USER_ERROR);
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\XsltProcessor;

final class ProcessXslt1DocumentsTest extends AbstractIntegrationTestCase
{
    public function testCollection(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testInclude(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testSimpleXml(): void
    {
        $xslDoc = \simplexml_load_file('Stubs/include.xsl');
        if ($xslDoc === false) {
            throw new \RuntimeException('Cannot load stylesheet');
        }

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testFromString(): void
    {
        $cwd = \getcwd();
        \chdir(\dirname(__DIR__).'/Stubs');

        $xslDoc = new DOMDocument('1.0', 'UTF-8');
        $xslDoc->loadXML((string)\file_get_contents('include.xsl'));

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);

        \chdir((string)$cwd);
    }

    public function testTransformToDoc(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        /** @var DOMDocument $nativeResult */
        $nativeResult = $native->transformToDoc($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToDoc($xmlDoc);

        $this->assertEquals($nativeResult->saveXML(), $transpilerResult->saveXML());
    }

    public function testTransformToUri(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->transformToUri($xmlDoc, 'php://temp');
        $nativeResult = \file_get_contents('php://temp');

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->transformToUri($xmlDoc, 'php://temp');
        $transpilerResult = \file_get_contents('php://temp');

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testPhpFunctionsAll(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/php-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->registerPHPFunctions();
        $nativeResult = \trim((string)$native->transformToXML($xmlDoc));

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->registerPHPFunctions();
        $transpilerResult = \trim($transpiler->transformToXML($xmlDoc));

        $this->assertEquals('false', $nativeResult);
        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testPhpFunctionsSome(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/php-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->registerPHPFunctions('strpos');
        $nativeResult = \trim((string)$native->transformToXML($xmlDoc));

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->registerPHPFunctions('strpos');
        $transpilerResult = \trim($transpiler->transformToXML($xmlDoc));

        $this->assertEquals('false', $nativeResult);
        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testOperators(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/operators.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->registerPHPFunctions();
        $nativeResult = \trim((string)$native->transformToXML($xmlDoc));

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->registerPHPFunctions();
        $transpilerResult = \trim($transpiler->transformToXML($xmlDoc));

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testAmpersandEscaped(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/ampersand-escaped.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->registerPHPFunctions();
        $nativeResult = \trim((string)$native->transformToXML($xmlDoc));

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpiler->registerPHPFunctions();
        $transpilerResult = \trim($transpiler->transformToXML($xmlDoc));

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testBug12(): void
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/item-types.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/item-types.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor(new NullCache());
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }
}

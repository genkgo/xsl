<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\XsltProcessor;

class ProcessXslt1DocumentsTest extends AbstractTestCase
{
    private $oldCwd;

    public function setUp()
    {
        $this->oldCwd = getcwd();
        chdir(dirname(__DIR__));
    }

    public function tearDown()
    {
        chdir($this->oldCwd);
    }

    public function testCollection()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testInclude()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testSimpleXml()
    {
        $xslDoc = simplexml_load_file('Stubs/include.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);
    }

    public function testFromString()
    {
        $cwd = getcwd();
        chdir(dirname(__DIR__).'/Stubs');

        $xslDoc = new DOMDocument('1.0', 'UTF-8');
        $xslDoc->loadXML(file_get_contents('include.xsl'));

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToXML($xmlDoc);

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToXML($xmlDoc);

        $this->assertEquals($nativeResult, $transpilerResult);

        chdir($cwd);
    }

    public function testTransformToDoc()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $nativeResult = $native->transformToDoc($xmlDoc);

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpilerResult = $transpiler->transformToDoc($xmlDoc);

        $this->assertEquals($nativeResult->saveXML(), $transpilerResult->saveXML());
    }

    public function testTransformToUri()
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/collection.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $native = new \XSLTProcessor();
        $native->importStylesheet($xslDoc);
        $native->transformToUri($xmlDoc, 'php://temp');
        $nativeResult = file_get_contents('php://temp');

        $transpiler = new XsltProcessor();
        $transpiler->importStylesheet($xslDoc);
        $transpiler->transformToUri($xmlDoc, 'php://temp');
        $transpilerResult = file_get_contents('php://temp');

        $this->assertEquals($nativeResult, $transpilerResult);
    }
}

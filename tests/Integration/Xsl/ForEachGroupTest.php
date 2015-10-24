<?php
namespace Genkgo\Xsl\Integration\Xsl;

use DOMDocument;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

class ForEachGroupTest extends AbstractIntegrationTestCase
{
    public function testByElement()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-element.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtest', trim($processor->transformToXml($data)));
    }

    public function testByAttribute()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-attribute.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtest', trim($processor->transformToXml($data)));
    }

    public function testByFunction()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-function.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtesttest', trim($processor->transformToXml($data)));
    }

    public function testEmpty()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-empty.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('', trim($processor->transformToXml($data)));
    }

    public function testByCurrentGroupingKey()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-current-grouping-key.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('ComposerGenkgo', trim($processor->transformToXml($data)));
    }

    public function testByCurrentGroup()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-current-group.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('Composer:-ComposerGenkgo:-CAMT-XSL-Migrations', trim($processor->transformToXml($data)));
    }

    public function testByAggregating()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-aggregating.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('20116044', trim($processor->transformToXml($data)));
    }

    public function testByAncestor()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-ancestor.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('2015-10-24 16:13:122015-10-24 16:13:12', trim($processor->transformToXml($data)));
    }

    public function testByTest()
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-test.xsl');

        $processor = new XsltProcessor();
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('1 CAMT packages', trim($processor->transformToXml($data)));
    }
}

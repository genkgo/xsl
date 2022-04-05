<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Integration\AbstractIntegrationTestCase;
use Genkgo\Xsl\XsltProcessor;

final class ForEachGroupTest extends AbstractIntegrationTestCase
{
    public function testByElement(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-element.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtest', \trim($processor->transformToXml($data)));
    }

    public function testByAttribute(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-attribute.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtest', \trim($processor->transformToXml($data)));
    }

    public function testByFunction(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-function.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('testtesttest', \trim($processor->transformToXml($data)));
    }

    public function testEmpty(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-empty.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('', \trim((string)$processor->transformToXml($data)));
    }

    public function testByCurrentGroupingKey(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-current-grouping-key.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('ComposerGenkgo', \trim($processor->transformToXml($data)));
    }

    public function testByCurrentGroupingKeySort(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-current-grouping-key-sort.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('GenkgoComposer', \trim($processor->transformToXml($data)));
    }

    public function testByCurrentGroup(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-current-group.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('Composer:-ComposerGenkgo:-CAMT-XSL-Migrations', \trim($processor->transformToXml($data)));
    }

    public function testCurrentGroupCurrentGroupingKeyWithForEach(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/current-group-without-for-each-group.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('', \trim((string)$processor->transformToXml($data)));
    }

    public function testByAggregating(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-aggregating.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('20116044', \trim($processor->transformToXml($data)));
    }

    public function testByAncestor(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-ancestor.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('2015-10-24 16:13:122015-10-24 16:13:12', \trim($processor->transformToXml($data)));
    }

    public function testByTest(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-test.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('1 CAMT packages', \trim($processor->transformToXml($data)));
    }

    public function testByPosition(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-position.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('22', \trim($processor->transformToXml($data)));
    }

    public function testWithNamespaceFunctions(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-namespace-functions.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('201120152014', \trim($processor->transformToXml($data)));
    }

    public function testByAttributeValueTemplates(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-avt.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('<span title="1 CAMT packages for key Genkgo">CAMT</span>', \trim($processor->transformToXml($data)));
    }

    public function testMultipleCalls(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-multiple.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals(
            'Author1Author2Author3Author4Author5Author1Author2Author3Author4Author1',
            \trim($processor->transformToXml($data))
        );
    }

    public function testByMultipleLevelSort(): void
    {
        $styleSheet = new DOMDocument();
        $styleSheet->load('Stubs/Xsl/ForEachGroup/group-by-multiple-level-sort.xsl');

        $processor = new XsltProcessor(new NullCache());
        $processor->importStyleSheet($styleSheet);

        $data = new DOMDocument();
        $data->load('Stubs/packages.xml');

        $this->assertEquals('CAMTMigrationsXSLComposer', \trim($processor->transformToXml($data)));
    }
}

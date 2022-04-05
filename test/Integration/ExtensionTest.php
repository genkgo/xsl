<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\ProcessorFactory;
use Genkgo\Xsl\Stubs\Extension\MyExtension;
use Genkgo\Xsl\XsltProcessor;

final class ExtensionTest extends AbstractIntegrationTestCase
{
    public function testXpathFunction(): void
    {
        $extension = new MyExtension();

        $factory = new ProcessorFactory(new NullCache(), [$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/my-extension.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('Hello World was called and received 20 arguments!', \trim($processorResult));
    }

    public function testXpathFunctionXsl1(): void
    {
        $extension = new MyExtension();

        $factory = new ProcessorFactory(new NullCache(), [$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/my-extension.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('Hello World was called and received 20 arguments!', \trim($processorResult));
    }

    public function testClosure(): void
    {
        $extension = new MyExtension();
        $factory = new ProcessorFactory(new NullCache(), [$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/closure.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('20', \trim($processorResult));
    }

    public function testMethod(): void
    {
        $extension = new MyExtension();
        $factory = new ProcessorFactory(new NullCache(), [$extension]);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/method.xsl');
        $xslDoc->documentElement->setAttribute('version', '1.0');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/collection.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $this->assertEquals('24', \trim($processorResult));
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Xsl\Cache\ArrayCache;
use Genkgo\Xsl\ProcessorFactory;
use Genkgo\Xsl\XsltProcessor;

final class CacheTest extends AbstractIntegrationTestCase
{
    public function testCacheRoot(): void
    {
        $cache = new ArrayCache();
        $factory = new ProcessorFactory($cache);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/combine-multiple-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $cacheKey = \str_replace('\\', '/', \dirname(__DIR__).'/Stubs/combine-multiple-functions.xsl');
        $cacheValue = $cache->get($cacheKey);

        $this->assertEquals(157, \trim($processorResult));
        $this->assertNotNull($cacheValue);

        $cacheValue = \str_replace('floor', 'ceiling', $cacheValue);
        $cache->set($cacheKey, $cacheValue);

        $this->assertEquals(158, \trim($processor->transformToXML($xmlDoc)));
    }

    public function testCacheInclude(): void
    {
        $cache = new ArrayCache();
        $factory = new ProcessorFactory($cache);

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/include2.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = $factory->newProcessor();
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $cacheKeyIncluded = \str_replace('\\', '/', \dirname(__DIR__).'/Stubs/combine-multiple-functions.xsl');
        $cacheKeyInclude = \str_replace('\\', '/', \dirname(__DIR__).'/Stubs/include2.xsl');
        $cacheIncluded = $cache->get($cacheKeyIncluded);
        $cacheInclude = $cache->get($cacheKeyInclude);

        $this->assertEquals(157, \trim($processorResult));
        $this->assertNotNull($cacheIncluded);
        $this->assertNotNull($cacheInclude);

        $cacheIncluded = \str_replace('floor', 'ceiling', $cacheIncluded);
        $cache->set($cacheKeyIncluded, $cacheIncluded);

        $this->assertEquals(158, \trim($processor->transformToXML($xmlDoc)));
    }
}

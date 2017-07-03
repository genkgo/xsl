<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Cache\Adapters\ArrayAdapter;
use Genkgo\Cache\Adapters\SimpleCallbackAdapter;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

class CacheTest extends AbstractIntegrationTestCase
{
    public function testCacheRoot()
    {
        $arrayCache = new ArrayAdapter();

        $config = new Config();
        $config->setCacheAdapter(new SimpleCallbackAdapter($arrayCache));

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/combine-multiple-functions.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $cacheKey = str_replace('\\', '/', dirname(__DIR__).'/Stubs/combine-multiple-functions.xsl');
        $cache = $arrayCache->get($cacheKey);

        $this->assertEquals(157, trim($processorResult));
        $this->assertNotNull($cache);

        $cache = str_replace('floor', 'ceiling', $cache);
        $arrayCache->set($cacheKey, $cache);

        $this->assertEquals(158, trim($processor->transformToXML($xmlDoc)));
    }

    public function testCacheInclude()
    {
        $arrayCache = new ArrayAdapter();

        $config = new Config();
        $config->setCacheAdapter(new SimpleCallbackAdapter($arrayCache));

        $xslDoc = new DOMDocument();
        $xslDoc->load('Stubs/include2.xsl');

        $xmlDoc = new DOMDocument();
        $xmlDoc->load('Stubs/combine-multiple-functions.xml');

        $processor = new XsltProcessor($config);
        $processor->importStylesheet($xslDoc);
        $processorResult = $processor->transformToXML($xmlDoc);

        $cacheKeyIncluded = str_replace('\\', '/', dirname(__DIR__).'/Stubs/combine-multiple-functions.xsl');
        $cacheKeyInclude = str_replace('\\', '/', dirname(__DIR__).'/Stubs/include2.xsl');
        $cacheIncluded = $arrayCache->get($cacheKeyIncluded);
        $cacheInclude = $arrayCache->get($cacheKeyInclude);

        $this->assertEquals(157, trim($processorResult));
        $this->assertNotNull($cacheIncluded);
        $this->assertNotNull($cacheInclude);

        $cacheIncluded = str_replace('floor', 'ceiling', $cacheIncluded);
        $arrayCache->set($cacheKeyIncluded, $cacheIncluded);

        $this->assertEquals(158, trim($processor->transformToXML($xmlDoc)));
    }
}

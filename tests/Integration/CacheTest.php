<?php
namespace Genkgo\Xsl\Integration;

use DOMDocument;
use Genkgo\Cache\Adapters\ArrayAdapter;
use Genkgo\Cache\Adapters\SimpleCallbackAdapter;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\XsltProcessor;

class CacheTest extends AbstractIntegrationTestCase {

    public function testCache () {
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

        $cacheKey = dirname(__DIR__).'/Stubs/combine-multiple-functions.xsl_';
        $cache = $arrayCache->get($cacheKey);

        $this->assertEquals(157, trim($processorResult));
        $this->assertNotNull($cache);

        $cache = str_replace('floor', 'ceiling', $cache);
        $arrayCache->set($cacheKey, $cache);

        $this->assertEquals(158, trim($processor->transformToXML($xmlDoc)));
    }

}
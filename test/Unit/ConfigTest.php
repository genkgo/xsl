<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Schema\XmlSchema;

class ConfigTest extends AbstractTestCase
{
    public function testSetExtensions()
    {
        $config = new Config();
        $config->setExtensions([new XmlSchema()]);
        $this->assertContainsOnlyInstancesOf(XmlSchema::class, $config->getExtensions());
    }

    public function testExcludePrefixes()
    {
        $config = new Config();
        $this->assertFalse($config->shouldExcludeResultPrefixes());

        $config->excludeResultPrefixes();
        $this->assertTrue($config->shouldExcludeResultPrefixes());
    }
}

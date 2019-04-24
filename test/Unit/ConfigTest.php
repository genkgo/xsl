<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Config;
use Genkgo\Xsl\Schema\XmlSchema;

final class ConfigTest extends AbstractTestCase
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

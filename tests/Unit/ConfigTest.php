<?php
namespace Genkgo\Xsl;

use Genkgo\Xsl\Schema\XmlSchema;

class ConfigTest extends AbstractTestCase
{
    public function testDefaultUpgrade()
    {
        $config = new Config();
        $this->assertTrue($config->shouldUpgradeToXsl2());
    }

    public function testSetExtensions()
    {
        $config = new Config();
        $config->setExtensions([new XmlSchema()]);
        $this->assertContainsOnlyInstancesOf(XmlSchema::class, $config->getExtensions());
    }

    public function testSetUpgradeToXsl()
    {
        $config = new Config();
        $config->setUpgradeToXsl2(true);
        $this->assertTrue($config->shouldUpgradeToXsl2());

        $config->setUpgradeToXsl2(false);
        $this->assertFalse($config->shouldUpgradeToXsl2());
    }
}

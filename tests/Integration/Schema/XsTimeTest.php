<?php
namespace Genkgo\Xsl\Integration\Schema;

class XsTimeTest extends AbstractSchemaTest
{
    public function testConstructor()
    {
        $result = $this->transformFile('Stubs/Schema/time.xsl');

        $this->assertContains('12:00:00', $result);
    }
}

<?php
namespace Genkgo\Xsl\Integration\Schema;

class XsDateTest extends AbstractSchemaTest
{
    public function testConstructor()
    {
        $result = $this->transformFile('Stubs/Schema/date.xsl');

        $this->assertContains('1995-05-10+00:00', $result);
    }
}

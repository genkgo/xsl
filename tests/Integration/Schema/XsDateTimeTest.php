<?php
namespace Genkgo\Xsl\Integration\Schema;

use Genkgo\Xsl\Exception\CastException;

class XsDateTimeTest extends AbstractSchemaTest
{
    public function testConstructor()
    {
        $result = $this->transformFile('Stubs/Schema/dateTime.xsl');

        $this->assertContains('1995-05-10T00:00:00+00:00', $result);
    }

    public function testWrongConstructor()
    {
        $this->setExpectedException(CastException::class);
        $this->transformFile('Stubs/Schema/dateTime-wrong-constructor.xsl');
    }
}

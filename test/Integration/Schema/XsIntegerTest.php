<?php 
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Schema;

use Genkgo\Xsl\Exception\CastException;

class XsIntegerTest extends AbstractSchemaTest
{
    public function testConstructor()
    {
        $result = $this->transformFile('Stubs/Schema/integer.xsl');

        $this->assertSame('1995', $result);
    }

    public function testWrongConstructor()
    {
        $this->expectException(CastException::class);
        $this->transformFile('Stubs/Schema/integer-wrong-constructor.xsl');
    }
}

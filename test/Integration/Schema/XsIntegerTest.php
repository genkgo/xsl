<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Schema;

use Genkgo\Xsl\Exception\TransformationException;

class XsIntegerTest extends AbstractSchemaTest
{
    public function testConstructor(): void
    {
        $result = $this->transformFile('Stubs/Schema/integer.xsl');

        $this->assertSame('1995', $result);
    }

    public function testWrongConstructor(): void
    {
        $this->expectException(TransformationException::class);
        $this->transformFile('Stubs/Schema/integer-wrong-constructor.xsl');
    }
}

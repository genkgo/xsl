<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

final class AttributeTest extends AbstractXslTest
{
    public function testAttributeSelect()
    {
        $this->assertSame('<cd attr="guitar"/><cd attr="guitar"/>', $this->transformFile('Stubs/Xsl/attribute.xsl'));
    }
}

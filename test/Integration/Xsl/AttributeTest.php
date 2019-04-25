<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

final class AttributeTest extends AbstractXslTest
{
    public function testAttributeSelect()
    {
        $this->assertSame(
            '<cd attr="guitar"/><cd attr="guitar"/>',
            $this->transformFile('Stubs/Xsl/ElementAttribute/attribute.xsl')
        );
    }

    public function testAttributeSelectSeparator()
    {
        $this->assertSame(
            '<cd attr="guitar, guitar"/>',
            $this->transformFile('Stubs/Xsl/ElementAttribute/separator.xsl')
        );
    }
}

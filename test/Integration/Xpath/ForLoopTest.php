<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class ForLoopTest extends AbstractXpathTest
{
    public function testSimple(): void
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/simple.xsl');
        $this->assertStringContainsString('1 2 3 4 5 6 7 8 9 10', $result);
    }

    public function testForEach(): void
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/for-each.xsl');
        $this->assertStringContainsString('12345678910', $result);
    }

    public function testElements(): void
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/elements.xsl');
        $this->assertEquals('1995 1996 1997', $result);
    }

    public function testPredicates(): void
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/predicates.xsl');
        $this->assertEquals('19951997', $result);
    }
}

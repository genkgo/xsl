<?php
namespace Genkgo\Xsl\Integration\Xpath;

class ForLoopTest extends AbstractXpathTest
{
    public function testSimple()
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/simple.xsl');
        $this->assertContains('1 2 3 4 5 6 7 8 9 10', $result);
    }

    public function testForEach()
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/for-each.xsl');
        $this->assertContains('12345678910', $result);
    }

    public function testElements()
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/elements.xsl');
        $this->assertEquals('1995 1996 1997', $result);
    }

    public function testPredicates()
    {
        $result = $this->transformFile('Stubs/Xpath/ForLoop/predicates.xsl');
        $this->assertEquals('19951997', $result);
    }
}

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

}

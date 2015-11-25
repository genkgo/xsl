<?php
namespace Genkgo\Xsl\Integration\Schema;

class XsSequenceTest extends AbstractSchemaTest
{
    public function testStringSequence()
    {
        $result = $this->transformFile('Stubs/Schema/string-sequence.xsl');
        $this->assertContains('a b c', $result);
    }

}

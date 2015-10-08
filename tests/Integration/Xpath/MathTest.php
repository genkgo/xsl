<?php
namespace Genkgo\Xsl\Integration\Xpath;

class MathTest extends AbstractXpathTest {

    public function testAbs () {
        $this->assertEquals(5, $this->transformFile('Stubs/Xpath/abs.xsl'));
    }

}
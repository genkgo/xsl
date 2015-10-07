<?php
namespace Genkgo\Xsl;

class XsltProcessorTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $decorator = new XsltProcessor();

        $this->assertTrue($decorator instanceof \XSLTProcessor);
    }
}

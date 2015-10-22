<?php
namespace Genkgo\Xsl\Integration\Xsl;

use Genkgo\Xsl\Xpath\Exception\InvalidArgumentException;

class AttributeValueTemplatesTest extends AbstractXslTest
{
    public function testSingleExpression()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/single-expression.xsl');

        $this->assertContains('#Ladyland', $result);
    }

    public function testEscaping()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/escaping.xsl');

        $this->assertContains('#{substring-after(title, \'Electric \')}', $result);
    }

    public function testMultipleExpression()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/multiple-expressions.xsl');

        $this->assertContains('Ladyland/Jimi Hendrix', $result);
    }

    public function testNotEscapedNotClosed()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/not-escaped-not-closed.xsl');
    }

    public function testInvalidEscaped()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/invalid-escaped.xsl');
    }
}

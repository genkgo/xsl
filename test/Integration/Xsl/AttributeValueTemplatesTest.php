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
        $this->expectException(InvalidArgumentException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/not-escaped-not-closed.xsl');
    }

    public function testInvalidEscaped()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/invalid-escaped.xsl');
    }

    public function testExpressionWithContent()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-with-content.xsl');

        $this->assertContains('#Ladyland#', $result);
    }

    public function testExpressionAndEscaping()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-and-escaping.xsl');

        $this->assertContains('#Ladyland}', $result);
    }

    public function testAmpersandEscaped()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-escaped.xsl');

        $this->assertContains('link?x=y&amp;a=b', $result);
    }

    public function testAmpersandGreaterThan()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-greater-than.xsl');

        $this->assertContains('link?x=y&amp;year=1997', $result);
    }
}

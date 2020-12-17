<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use Genkgo\Xsl\Exception\TransformationException;

final class AttributeValueTemplatesTest extends AbstractXslTest
{
    public function testSingleExpression()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/single-expression.xsl');

        $this->assertStringContainsString('#Ladyland', $result);
    }

    public function testEscaping()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/escaping.xsl');

        $this->assertStringContainsString('#{substring-after(title, \'Electric \')}', $result);
    }

    public function testMultipleExpression()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/multiple-expressions.xsl');

        $this->assertStringContainsString('Ladyland/Jimi Hendrix', $result);
    }

    public function testNotEscapedNotClosed()
    {
        $this->expectException(TransformationException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/not-escaped-not-closed.xsl');
    }

    public function testInvalidEscaped()
    {
        $this->expectException(TransformationException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/invalid-escaped.xsl');
    }

    public function testExpressionWithContent()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-with-content.xsl');

        $this->assertStringContainsString('#Ladyland#', $result);
    }

    public function testExpressionAndEscaping()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-and-escaping.xsl');

        $this->assertStringContainsString('#Ladyland}', $result);
    }

    public function testAmpersandEscaped()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-escaped.xsl');

        $this->assertStringContainsString('link?x=y&amp;a=b', $result);
    }

    public function testAmpersandGreaterThan()
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-greater-than.xsl');

        $this->assertStringContainsString('link?x=y&amp;year=1997', $result);
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xsl;

use Genkgo\Xsl\Exception\TransformationException;

final class AttributeValueTemplatesTest extends AbstractXslTest
{
    public function testSingleExpression(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/single-expression.xsl');

        $this->assertStringContainsString('#Ladyland', $result);
    }

    public function testEscaping(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/escaping.xsl');

        $this->assertStringContainsString('#{substring-after(title, \'Electric \')}', $result);
    }

    public function testMultipleExpression(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/multiple-expressions.xsl');

        $this->assertStringContainsString('Ladyland/Jimi Hendrix', $result);
    }

    public function testNotEscapedNotClosed(): void
    {
        $this->expectException(TransformationException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/not-escaped-not-closed.xsl');
    }

    public function testInvalidEscaped(): void
    {
        $this->expectException(TransformationException::class);
        $this->transformFile('Stubs/Xsl/AttributeValueTemplates/invalid-escaped.xsl');
    }

    public function testExpressionWithContent(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-with-content.xsl');

        $this->assertStringContainsString('#Ladyland#', $result);
    }

    public function testExpressionAndEscaping(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/expression-and-escaping.xsl');

        $this->assertStringContainsString('#Ladyland}', $result);
    }

    public function testAmpersandEscaped(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-escaped.xsl');

        $this->assertStringContainsString('link?x=y&amp;a=b', $result);
    }

    public function testAmpersandGreaterThan(): void
    {
        $result = $this->transformFile('Stubs/Xsl/AttributeValueTemplates/ampersand-greater-than.xsl');

        $this->assertStringContainsString('link?x=y&amp;year=1997', $result);
    }
}

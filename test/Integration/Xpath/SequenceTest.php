<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Integration\Xpath;

class SequenceTest extends AbstractXpathTest
{
    public function testConstructorString(): void
    {
        $result = $this->transformFile('Stubs/Xpath/Sequence/constructor-string.xsl');
        $this->assertStringContainsString('a b c', $result);
    }

    public function testMultipleParenthesis(): void
    {
        $result = $this->transformFile('Stubs/Xpath/Sequence/constructor-multiple-parenthesis.xsl');
        $this->assertStringContainsString('a b c', $result);
    }

    public function testConstructorInteger(): void
    {
        $result = $this->transformFile('Stubs/Xpath/Sequence/constructor-integer.xsl');
        $this->assertStringContainsString('1 2 3', $result);
    }

    public function testReverse(): void
    {
        $this->assertEquals(
            'genkgo/xsl by transpiled is xsl2',
            $this->transformFile('Stubs/Xpath/Sequence/reverse.xsl')
        );
    }

    public function testInsertBefore(): void
    {
        $this->assertEquals(
            'xsl2 and xpath2 is transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/insert-before.xsl')
        );
    }

    public function testRemove(): void
    {
        $this->assertEquals(
            'xsl2 transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/remove.xsl')
        );
    }

    public function testSubsequenceOffset(): void
    {
        $this->assertEquals(
            'transpiled by genkgo/xsl',
            $this->transformFile('Stubs/Xpath/Sequence/subsequence-offset.xsl')
        );
    }

    public function testSubsequenceFromLength(): void
    {
        $this->assertEquals(
            'transpiled by',
            $this->transformFile('Stubs/Xpath/Sequence/subsequence-length.xsl')
        );
    }

    public function testDistinctValuesElement(): void
    {
        $this->assertEquals(
            'guitar',
            $this->transformFile('Stubs/Xpath/Sequence/distinct-values-elements.xsl')
        );
    }

    public function testDistinctValuesAttribute(): void
    {
        $this->assertEquals(
            '12:00:00+00:00 09:00:00+00:00',
            $this->transformFile('Stubs/Xpath/Sequence/distinct-values-attributes.xsl')
        );
    }

    public function testDistinctValuesScalars(): void
    {
        $this->assertEquals(
            '1 2 3 4',
            $this->transformFile('Stubs/Xpath/Sequence/distinct-values-scalars.xsl')
        );
    }

    public function testAmpersand(): void
    {
        $this->assertEquals(
            'some string',
            $this->transformFile('Stubs/Xpath/Sequence/ampersand.xsl')
        );
    }

    public function testUnordered(): void
    {
        $expected = \explode(' ', 'genkgo/xsl by transpiled is xsl2');
        $items = \explode(' ', $this->transformFile('Stubs/Xpath/Sequence/unordered.xsl'));

        foreach ($items as $item) {
            $this->assertContains($item, $expected);
        }
    }

    public function testIndexOfText(): void
    {
        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Sequence/index-of-text.xsl', [
            'param1' => 'Hello',
            'param2' => 'World'
        ]));

        $this->assertEquals('7', $this->transformFile('Stubs/Xpath/Sequence/index-of-text.xsl', [
            'param1' => 'Hello World',
            'param2' => 'World'
        ]));
    }

    public function testIndexOfSequence(): void
    {
        $this->assertEquals('2', $this->transformFile('Stubs/Xpath/Sequence/index-of-sequence.xsl', [
            'param' => '1997',
        ]));

        $this->assertEquals('false', $this->transformFile('Stubs/Xpath/Sequence/index-of-sequence.xsl', [
            'param' => '2000',
        ]));
    }
}

<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Unit;

use Genkgo\Xsl\AbstractTestCase;
use Genkgo\Xsl\Xpath\Lexer;
use Genkgo\Xsl\Xpath\MatchingIterator;

class MatchingIteratorTest extends AbstractTestCase
{
    public function testWithMatches(): void
    {
        $lexer = new Lexer(['/', '/', '*']);
        $iterator = new MatchingIterator($lexer, '/\//');

        $results = [0, 1] ;
        $index = 0;
        foreach ($iterator as $key => $value) {
            $this->assertEquals('/', $value);
            $this->assertEquals($results[$index], $key);
            $index++;
        }

        $this->assertEquals(2, \iterator_count($iterator));
    }

    public function testNoMatches(): void
    {
        $lexer = new Lexer(['/', '/', '*']);
        $iterator = new MatchingIterator($lexer, '/a/');

        $this->assertEquals(0, \iterator_count($iterator));
    }

    public function testEmpty(): void
    {
        $lexer = new Lexer([]);
        $iterator = new MatchingIterator($lexer, '/a/');

        $this->assertEquals(0, \iterator_count($iterator));
    }

    public function testOtherSeekPosition(): void
    {
        $lexer = new Lexer(['/', '/', '*']);
        $lexer->seek(1);
        $iterator = new MatchingIterator($lexer, '/\//');

        $results = [1] ;
        $index = 0;
        while ($iterator->valid()) {
            $this->assertEquals('/', $iterator->current());
            $this->assertEquals($results[$index], $iterator->key());
            $index++;
            $iterator->next();
        }

        $this->assertEquals(1, \iterator_count($iterator));
    }

    public function testDirectionDown(): void
    {
        $lexer = new Lexer(['/', '/', '*']);
        $iterator = new MatchingIterator($lexer, '/\//', MatchingIterator::DIRECTION_DOWN);

        $this->assertEquals(1, \iterator_count($iterator));
    }

    public function testDirectionDownOtherSeekPosition(): void
    {
        $lexer = new Lexer(['/', '/', '*']);
        $lexer->seek(1);

        $iterator = new MatchingIterator($lexer, '/\//', MatchingIterator::DIRECTION_DOWN);
        $this->assertEquals(2, \iterator_count($iterator));
    }
}

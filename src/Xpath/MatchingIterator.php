<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Xpath;

use Iterator;

final class MatchingIterator implements Iterator
{
    const DIRECTION_UP = 1;

    const DIRECTION_DOWN = -1;

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $direction;

    /**
     * @param Lexer $lexer
     * @param string $regex
     * @param int $direction
     */
    public function __construct(Lexer $lexer, string $regex, int $direction = self::DIRECTION_UP)
    {
        $this->lexer = $lexer;
        $this->regex = $regex;
        $this->direction = $direction;
        $this->start();
    }

    public function current(): string
    {
        return $this->lexer->peek($this->position);
    }

    public function next(): void
    {
        $this->position += $this->direction;
        $end = $this->direction === self::DIRECTION_UP ? $this->lexer->count() : 0;

        for ($i = $this->position; $this->compare($i, $end); $i += $this->direction) {
            $token = $this->lexer->peek($i);
            if (\preg_match($this->regex, $token) === 1) {
                $this->position = $i;
                return;
            }
        }

        $this->position = -1;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->position !== -1;
    }

    public function rewind(): void
    {
        $this->start();
    }
    
    private function start()
    {
        $this->position = $this->lexer->key() + ($this->direction * -1);
        $this->next();
    }

    /**
     * @param int $index
     * @param int $end
     * @return bool
     */
    private function compare(int $index, int $end): bool
    {
        return $this->direction === self::DIRECTION_UP ? $index < $end : $index >= $end;
    }
}

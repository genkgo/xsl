<?php
namespace Genkgo\Xsl\Xpath;

use Iterator;

/**
 * Class MatchingIterator
 * @package Genkgo\Xsl\Xpath
 */
class MatchingIterator implements Iterator {

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
     * @param $regex
     * @param int $direction
     */
    public function __construct (Lexer $lexer, $regex, $direction = self::DIRECTION_UP) {
        $this->lexer = $lexer;
        $this->regex = $regex;
        $this->direction = $direction;
        $this->start();
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->lexer->peek($this->position);
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->position += $this->direction;
        $end = $this->direction === self::DIRECTION_UP ? $this->lexer->count() : 0;

        for ($i = $this->position; $this->compare($i, $end); $i += $this->direction) {
            $token = $this->lexer->peek($i);
            if (preg_match($this->regex, $token) === 1) {
                $this->position = $i;
                return;
            }
        }

        $this->position = -1;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return $this->position !== -1;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->start();
    }

    /**
     *
     */
    private function start () {
        $this->position = $this->lexer->key() + ($this->direction * -1);
        $this->next();
    }

    /**
     * @param $index
     * @param $end
     * @return bool
     */
    private function compare ($index, $end) {
        return $this->direction === self::DIRECTION_UP ? $index < $end : $index >= $end;
    }

}
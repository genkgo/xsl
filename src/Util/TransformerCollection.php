<?php
namespace Genkgo\Xsl\Util;

use ArrayIterator;
use Genkgo\Xsl\TransformerInterface;
use IteratorAggregate;

/**
 * Class TransformerCollection
 * @package Genkgo\Xsl\Util
 */
final class TransformerCollection implements IteratorAggregate {

    /**
     * @var array|TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param TransformerInterface $transformer
     */
    public function attach (TransformerInterface $transformer) {
        $this->transformers[] = $transformer;
    }

    /**
     * @param TransformerInterface $transformer
     */
    public function detach (TransformerInterface $transformer) {
        $key = array_search($transformer, $this->transformers, true);
        if ($key !== false) {
            unset($this->transformers[$key]);
        }
    }

    /**
     * @return ArrayIterator|TransformerInterface[]
     */
    public function getIterator() {
        return new ArrayIterator($this->transformers);
    }
}
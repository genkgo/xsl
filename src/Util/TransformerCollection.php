<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Util;

use ArrayIterator;
use Genkgo\Xsl\TransformerInterface;
use IteratorAggregate;

final class TransformerCollection implements IteratorAggregate
{
    /**
     * @var array|TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param TransformerInterface $transformer
     */
    public function attach(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @return ArrayIterator|TransformerInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->transformers);
    }
}

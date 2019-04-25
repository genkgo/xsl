<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Util;

use Genkgo\Xsl\TransformerInterface;

final class TransformerCollection implements \IteratorAggregate
{
    /**
     * @var array|TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param TransformerInterface $transformer
     */
    public function attach(TransformerInterface $transformer): void
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @return \ArrayIterator|TransformerInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->transformers);
    }
}

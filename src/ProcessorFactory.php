<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Psr\SimpleCache\CacheInterface;

final class ProcessorFactory
{
    /**
     * @var array|XmlNamespaceInterface[]
     */
    private $extensions = [];

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param CacheInterface $cache
     * @param array $extensions
     */
    public function __construct(CacheInterface $cache, array $extensions = [])
    {
        $this->cache = $cache;
        $this->extensions = $extensions;
    }

    /**
     * @return XsltProcessor
     */
    public function newProcessor(): XsltProcessor
    {
        return new XsltProcessor(
            $this->cache,
            $this->extensions
        );
    }
}

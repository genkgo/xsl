<?php
namespace Genkgo\Xsl;

use Genkgo\Cache\CallbackCacheInterface;
use Genkgo\Xsl\Exception\CacheDisabledException;

/**
 * Class Config
 * @package Genkgo\Xsl
 */
final class Config
{
    /**
     * @var array
     */
    private $extensions = [];
    /**
     * @var CallbackCacheInterface
     */
    private $cacheAdapter;

    /**
     * @param XmlNamespaceInterface[] $extensions
     * @return Config
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @param XmlNamespaceInterface $extension
     * @return Config
     */
    public function addExtension(XmlNamespaceInterface $extension)
    {
        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * @return XmlNamespaceInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param CallbackCacheInterface $cacheAdapter
     */
    public function setCacheAdapter(CallbackCacheInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * @return CallbackCacheInterface
     * @throws CacheDisabledException
     */
    public function getCacheAdapter()
    {
        if ($this->cacheAdapter === null) {
            throw new CacheDisabledException();
        }

        return $this->cacheAdapter;
    }

    /**
     * @return Config
     */
    public static function fromDefault()
    {
        return new static();
    }
}

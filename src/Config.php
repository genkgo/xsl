<?php
declare(strict_types=1);

namespace Genkgo\Xsl;

use Genkgo\Cache\CallbackCacheInterface;
use Genkgo\Xsl\Exception\CacheDisabledException;

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
     * @var bool
     */
    private $excludeResultPrefixes = false;

    /**
     * @var bool
     */
    private $disableEntities = true;

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
     * @return Config
     */
    public function setCacheAdapter(CallbackCacheInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
        return $this;
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
    public function excludeResultPrefixes()
    {
        $this->excludeResultPrefixes = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function shouldExcludeResultPrefixes()
    {
        return $this->excludeResultPrefixes;
    }

    /**
     * @return Config
     */
    public function enableEntities()
    {
        $this->disableEntities = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEntitiesDisabled()
    {
        return $this->disableEntities;
    }

    /**
     * @return Config
     */
    public static function fromDefault()
    {
        return new static();
    }
}

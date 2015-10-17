<?php
namespace Genkgo\Xsl;

use Genkgo\Cache\CallbackCacheInterface;
use Genkgo\Xsl\Exception\CacheDisabledException;

final class Config {

    /**
     * @var bool
     */
    private $upgradeToXsl2 = true;
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
    public function setExtensions (array $extensions) {
        $this->extensions = $extensions;
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
     * @return boolean
     */
    public function shouldUpgradeToXsl2()
    {
        return $this->upgradeToXsl2;
    }

    /**
     * @param boolean $upgradeToXsl2
     * @return Config
     */
    public function setUpgradeToXsl2($upgradeToXsl2)
    {
        $this->upgradeToXsl2 = $upgradeToXsl2;
        return $this;
    }

    /**
     * @param CallbackCacheInterface $cacheAdapter
     */
    public function setCacheAdapter (CallbackCacheInterface $cacheAdapter) {
        $this->cacheAdapter = $cacheAdapter;
    }

    /**
     * @return CallbackCacheInterface
     * @throws CacheDisabledException
     */
    public function getCacheAdapter() {
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
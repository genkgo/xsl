<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Cache;

use Psr\SimpleCache\CacheInterface;

final class ArrayCache implements CacheInterface
{
    /**
     * @var array
     */
    private $memory = [];

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->memory[$key])) {
            return $this->getNonExpiredCacheOrDefault($this->memory[$key], $default);
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null|\DateInterval $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        $expires = null;

        if (\is_int($ttl)) {
            $expires = \time() + $ttl;
        }
        if ($ttl instanceof \DateInterval) {
            $expires = (new \DateTimeImmutable('now'))->add($ttl)->format('U');
        }

        $this->memory[$key] = [$value, $expires];
        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        unset($this->memory[$key]);
        return true;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $this->memory = [];
        return true;
    }

    /**
     * @param iterable $keys
     * @param mixed $default
     * @return iterable
     */
    public function getMultiple($keys, $default = null)
    {
        foreach (\array_keys($this->memory) as $key) {
            yield $this->get($key, $default);
        }
    }

    /**
     * @param iterable $values
     * @param null|int|\DateInterval $ttl
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * @param iterable $keys
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        foreach (\array_keys($this->memory) as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->memory[$key]);
    }

    /**
     * @param array $keyData
     * @param mixed|null $default
     * @return mixed
     */
    private function getNonExpiredCacheOrDefault(array $keyData, $default = null)
    {
        [$value, $expires] = $keyData;

        if ($expires === null || $expires > \time()) {
            return $value;
        }

        return $default;
    }
}

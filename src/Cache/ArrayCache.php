<?php
declare(strict_types=1);

namespace Genkgo\Xsl\Cache;

use Psr\SimpleCache\CacheInterface;

final class ArrayCache implements CacheInterface
{
    private array $memory = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (isset($this->memory[$key])) {
            return $this->getNonExpiredCacheOrDefault($this->memory[$key], $default);
        }

        return $default;
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
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

    public function delete(string $key): bool
    {
        unset($this->memory[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->memory = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->get($key, $default);
        }
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach (\array_keys($this->memory) as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return isset($this->memory[$key]);
    }

    private function getNonExpiredCacheOrDefault(array $keyData, mixed $default = null): mixed
    {
        [$value, $expires] = $keyData;

        if ($expires === null || $expires > \time()) {
            return $value;
        }

        return $default;
    }
}

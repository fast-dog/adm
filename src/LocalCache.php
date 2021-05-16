<?php

namespace FastDog\Adm;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Class LocalCache
 * @package App\Admin
 */
class LocalCache
{
    /** @var bool */
    const ENABLE = true;

    /** @var array */
    private array $storage = [];

    /**
     * @param $key
     * @param $values
     * @param  string  $tags
     */
    public function setCache($key, $values, $tags = 'default'): void
    {
        if (self::ENABLE) {
            $key .= '-'.config('app.env');
            (config('cache.default') === 'redis') ? Cache::tags($tags)->put($key, $values, config('adm.resource_ttl', 60)) :
                Cache::put($key, $values, config('adm.resource_ttl', 60));
        }
    }

    /**
     * @param $key
     * @param  null  $default
     * @param  string  $tags
     * @return array|mixed
     */
    public function getCache($key, $default = null, $tags = 'default')
    {
        $key .= '-'.config('app.env');

        return (config('cache.default') === 'redis') ?
            Cache::tags($tags)->get($key, $default) : Cache::get($key, $default);
    }

    /**
     * @param $key
     * @param  Closure  $callback
     * @param  string  $tags
     * @return array|mixed
     */
    public function withCache($key, Closure $callback, $tags = 'default')
    {
        $result = (self::ENABLE) ? $this->getCache($key, null, $tags) : null;

        if (null === $result) {
            $result = $callback();
            $this->setCache($key, $result, $tags);
        }

        return $result;
    }
}

<?php

/*
 * This file is part of the jimchen/yii2-flysystem-contract.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\flysystem;

use League\Flysystem\Cached\Storage\AbstractCache;
use yii\caching\Cache;

class YiiCache extends AbstractCache
{
    /**
     * @var Cache
     */
    protected $yiiCache;
    /**
     * @var string
     */
    protected $key;
    /**
     * @var int
     */
    protected $duration;

    /**
     * YiiCache constructor.
     *
     * @param Cache  $yiiCache
     * @param string $key
     * @param int    $duration
     */
    public function __construct(Cache $yiiCache, $key, $duration = 0)
    {
        $this->yiiCache = $yiiCache;
        $this->key = $key;
        $this->duration = $duration;
    }

    /**
     * Store the cache.
     */
    public function save()
    {
        $this->yiiCache->set($this->key, $this->getForStorage(), $this->duration);
    }

    /**
     * Load the cache.
     */
    public function load()
    {
        $contents = $this->yiiCache->get($this->key);

        if (false !== $contents) {
            $this->setFromStorage($contents);
        }
    }
}

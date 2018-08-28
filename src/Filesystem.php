<?php

/*
 * This file is part of the jimchen/yii2-flysystem-contract.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace jimchen\flysystem;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Config;
use League\Flysystem\Filesystem as NativeFilesystem;
use League\Flysystem\Replicate\ReplicateAdapter;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;

/**
 * @method \League\Flysystem\FilesystemInterface addPlugin(\League\Flysystem\PluginInterface $plugin)
 * @method void                                  assertAbsent(string $path)
 * @method void                                  assertPresent(string $path)
 * @method bool                                  copy(string $path, string $newpath)
 * @method bool                                  createDir(string $dirname, array $config = null)
 * @method bool                                  delete(string $path)
 * @method bool                                  deleteDir(string $dirname)
 * @method \League\Flysystem\Handler             get(string $path, \League\Flysystem\Handler $handler = null)
 * @method \League\Flysystem\AdapterInterface    getAdapter()
 * @method \League\Flysystem\Config              getConfig()
 * @method array|false                           getMetadata(string $path)
 * @method string|false                          getMimetype(string $path)
 * @method int|false                             getSize(string $path)
 * @method int|false                             getTimestamp(string $path)
 * @method string|false                          getVisibility(string $path)
 * @method array                                 getWithMetadata(string $path, array $metadata)
 * @method bool                                  has(string $path)
 * @method array                                 listContents(string $directory = '', boolean $recursive = false)
 * @method array                                 listFiles(string $path = '', boolean $recursive = false)
 * @method array                                 listPaths(string $path = '', boolean $recursive = false)
 * @method array                                 listWith(array $keys = [], $directory = '', $recursive = false)
 * @method bool                                  put(string $path, string $contents, array $config = [])
 * @method bool                                  putStream(string $path, resource $resource, array $config = [])
 * @method string|false                          read(string $path)
 * @method string|false                          readAndDelete(string $path)
 * @method resource|false                        readStream(string $path)
 * @method bool                                  rename(string $path, string $newpath)
 * @method bool                                  setVisibility(string $path, string $visibility)
 * @method bool                                  update(string $path, string $contents, array $config = [])
 * @method bool                                  updateStream(string $path, resource $resource, array $config = [])
 * @method bool                                  write(string $path, string $contents, array $config = [])
 * @method bool                                  writeStream(string $path, resource $resource, array $config = [])
 */
abstract class Filesystem extends Component
{
    /**
     * @var Config|array|null
     */
    public $config;
    /**
     * @var string|null
     */
    public $cache;
    /**
     * @var string
     */
    public $cacheKey = 'flysystem';
    /**
     * @var string|null
     */
    public $replica;
    /**
     * @var int
     */
    public $cacheDuration = 3600;
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $adapter = $this->prepareAdapter();

        if (null !== $this->cache) {
            $cache = \Yii::$app->get($this->cache);

            if (!$cache instanceof Cache) {
                throw new InvalidConfigException('The "cache" property must be an instance of \yii\caching\Cache subclasses.');
            }

            $adapter = new CachedAdapter($adapter, new YiiCache($cache, $this->cacheKey, $this->cacheDuration));
        }

        if (null !== $this->replica) {
            /* @var \League\Flysystem\Filesystem $filesystem */
            $filesystem = \Yii::$app->get($this->replica);

            if (!$filesystem instanceof Filesystem) {
                throw new InvalidConfigException('The "replica" property must be an instance of \creocoder\flysystem\Filesystem subclasses.');
            }

            $adapter = new ReplicateAdapter($adapter, $filesystem->getAdapter());
        }

        $this->filesystem = new NativeFilesystem($adapter, $this->config);
    }

    /**
     * @return AdapterInterface
     */
    abstract protected function prepareAdapter();

    /**
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->filesystem, $method], $params);
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }
}

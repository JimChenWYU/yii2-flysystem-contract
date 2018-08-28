<?php

/*
 * This file is part of the jimchen/yii2-flysystem-contract.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace flysystem\tests;

use jimchen\flysystem\YiiCache;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Config;
use PHPUnit\Framework\TestCase;
use yii\caching\Cache;

class YiiCacheTest extends TestCase
{
    public function testInitYiiCache()
    {
        $contents = 'contents';
        $config = new Config();

        $result = json_encode([[
            'key' => [
                'type'     => 'file',
                'path'     => 'key',
                'size'     => 8,
                'contents' => $contents,
            ]
        ],[]]);

        $m1 = $this->createMock(Cache::class);
        $m1->method('get')->with('cacheKey')->willReturn($result);
        $m1->method('set')->with('cacheKey', $result, 3600);

        $cache = new YiiCache($m1, 'cacheKey', 3600);

        $m2 = $this->createMock(AdapterInterface::class);
        $m2->method('write')->with('key', $contents, $config)->willReturn([
            'type'     => 'file',
            'path'     => 'key',
            'size'     => 8,
            'contents' => $contents,
        ]);
        $m2->method('read')->with('key')->willReturn([
            'type' => 'file',
            'path' => 'key',
            'contents' => $contents
        ]);

        $m3 = $this->getMockBuilder(CachedAdapter::class)
            ->setConstructorArgs([$m2, $cache])
            ->getMock();

        $m3->write('key', $contents, $config);

        $this->assertSame([
            'type'     => 'file',
            'path'     => 'key',
            'size'     => 8,
            'contents' => $contents,
        ], $cache->read('key'));
    }
}

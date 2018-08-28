# yii2-flysystem-contract

The flysystem extension contract for the Yii framework, the abstract class is from [creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem), the repo's purpose is to separate the abstraction and implementation.

## Installing

```shell
$ composer require jimchen/yii2-flysystem-contract -vvv
```

## Usage

```php

use jimchen\flysystem\Filesystem;

class xxxFilesystem extends Filesystem
{
    /**
     * @return \League\Flysystem\AdapterInterface
     * /
    protected function prepareAdapter()
    {
        // TODO: Implement __callStatic() method.
    }
}

```

`\jimchen\flysystem\Filesystem` is a [Yii2 Component](https://www.yiiframework.com/doc/guide/2.0/en/structure-application-components), so your `xxxFilesystem` can register as a [Yii2 Component](https://www.yiiframework.com/doc/guide/2.0/en/structure-application-components).

## Thanks

[creocoder/yii2-flysystem](https://github.com/creocoder/yii2-flysystem)

## License

MIT
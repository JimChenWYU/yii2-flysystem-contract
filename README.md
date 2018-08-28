# yii2-flysystem-contract

The flysystem extension contract for the Yii framework

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

## License

MIT
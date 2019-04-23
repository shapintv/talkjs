# TalkJS PHP SDK

[![latest version](https://img.shields.io/github/release/shapintv/talkjs.svg?style=flat-square)](https://github.com/shapintv/talkjs/releases)
[![build status](https://img.shields.io/travis/shapintv/talkjs.svg?style=flat-square)](https://travis-ci.com/shapintv/talkjs)
[![code coverage](https://img.shields.io/scrutinizer/coverage/g/shapintv/talkjs.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/talkjs)
[![quality score](https://img.shields.io/scrutinizer/g/shapintv/talkjs.svg?style=flat-square)](https://scrutinizer-ci.com/g/shapintv/talkjs)
[![total downloads](https://img.shields.io/packagist/dt/shapin/talkjs.svg?style=flat-square)](https://packagist.org/packages/shapin/talkjs)


## Install

Via Composer

``` bash
$ composer require shapintv/talkjs
```

## Usage

``` php
$client = TalkJSClient::create($secretKey, $appId);
// Create a customer
$client->users()->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

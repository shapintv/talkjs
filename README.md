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

### Create a `TalkJSClient`

Using the static `create` method:

``` php
$client = TalkJSClient::create($secretKey, $appId);
```

Using your own `HttpClient`:

```php
$client = new TalkJSClient($myHttpClient);
```

Learn how to create your own client on [PHP-HTTP documentation](http://docs.php-http.org/en/latest/).
If you use your own client, be sure to configure it properly. See [HttpClientConfigurator](src/HttpClientConfigurator.php) to see what's needed.

### Deal with users

```php
// Create or update a user
$client->users()->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);

// Retrieve a user
$user = $client->users()->get('my_custom_id');
```

### Deal with conversations

```php
// Create or update a user
$client->conversations()->createOrUpdate('my_custom_id', [
    'subject' => 'My new conversation',
]);

// Retrive a conversation
$conversation = $client->conversations()->get('my_custom_id');

// Find conversations
$conversations = $client->conversations()->find();

// Join a conversation
$client->conversation()->join('my_conversation_id', 'my_user_id');

// Leave a conversation
$client->conversation()->leave('my_conversation_id', 'my_user_id');
```

### Integration with symfony

Create a new HttpClient:

```yml
httplug:
    clients:
        talkjs:
            plugins:
                - 'httplug.plugin.content_length'
                - 'httplug.plugin.redirect'
                - add_host:
                    host: 'https://api.talkjs.com/'
                - add_path:
                    path: '/v1/%env(TALKJS_APP_ID)%'
                - header_append:
                    headers:
                        'User-Agent': 'Shapin/TalkJS (https://github.com/shapintv/talkjs)'
                        'Content-Type': 'application/json'
                - authentication:
                    bearer:
                        type: 'bearer'
                        token: '%env(TALKJS_SECRET_KEY)%'
```

Then create your service:

```yml
services:
    Shapin\TalkJS\TalkJSClient:
        arguments: ['@httplug.client.talkjs']
```

You're done!

One day, I may consider creating a bundle in order to bootstrap this SDK...

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

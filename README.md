# TalkJS PHP SDK

[![Latest version](https://img.shields.io/github/release/shapintv/talkjs.svg?style=flat-square)](https://github.com/shapintv/talkjs/releases)
[![Build status](https://img.shields.io/travis/shapintv/talkjs.svg?style=flat-square)](https://travis-ci.com/shapintv/talkjs)
[![Total downloads](https://img.shields.io/packagist/dt/shapin/talkjs.svg?style=flat-square)](https://packagist.org/packages/shapin/talkjs)


## Install

Via Composer

``` bash
$ composer require shapin/talkjs
```

## Usage

### Create a `TalkJSClient`

```php
use Shapin\TalkJS\TalkJSClient;
use Symfony\Component\HttpClient\HttpClient;

$httpClient = HttpClient::create([
    'base_uri' => 'https://api.talkjs.com/v1/'.self::APP_ID.'/',
    'auth_bearer' => self::SECRET_KEY,
    'headers' => [
        'Content-Type' => 'application/json',
    ],
]);

$talkJSClient = new TalkJSClient($httpClient);
```

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
framework:
    http_client:
        scoped_clients:
            talkjs.client:
                auth_bearer: '%env(TALKJS_SECRET_KEY)%'
                base_uri: 'https://api.talkjs.com/v1/%env(TALKJS_APP_ID)%/'
                headers:
                    'Content-Type': 'application/json'
```

Then create your service:

```yml
services:
    Shapin\TalkJS\TalkJSClient: ~
```

You're done!

One day, I may consider creating a bundle in order to bootstrap this SDK...

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

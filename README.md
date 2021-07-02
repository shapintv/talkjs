# TalkJs PHP SDK

[![Latest version](https://img.shields.io/github/release/CarAndClassic/talkjs.svg?style=flat-square)](https://github.com/CarAndClassic/talkjs/releases)
[![Total downloads](https://img.shields.io/packagist/dt/CarAndClassic/talkjs.svg?style=flat-square)](https://packagist.org/packages/CarAndClassic/talkjs)


## Install

Via Composer

``` bash
$ composer require CarAndClassic/talkjs
```

## Usage

### Create a `TalkJsClient`

```php
use CarAndClassic\TalkJs\TalkJsClient;
$talkJSClient = new TalkJsClient($appId, $secretKey);
```

### Users

```php
// Create or update a user
$talkJsClient->users()->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);

// Retrieve a user
$user = $talkJsClient->users()->get('my_custom_id');
```

### Conversations

```php
// Create or update a conversation
$talkJsClient->conversations()->createOrUpdate('my_custom_id', [
    'subject' => 'My new conversation',
]);

// Retrive a conversation
$conversation = $talkJsClient->conversations()->get('my_custom_id');

// Find conversations
$conversations = $talkJsClient->conversations()->find();

// Join a conversation
$talkJsClient->conversation()->join('my_conversation_id', 'my_user_id');

// Leave a conversation
$talkJsClient->conversation()->leave('my_conversation_id', 'my_user_id');
```

### Messages



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
    CarAndClassic\TalkJs\TalkJsClient: ~
```

You're done!

One day, I may consider creating a bundle in order to bootstrap this SDK...

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

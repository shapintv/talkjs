# TalkJS PHP SDK

[![Latest version](https://img.shields.io/github/release/shapintv/talkjs.svg?style=flat-square)](https://github.com/shapintv/talkjs/releases)
[![Build status](https://img.shields.io/travis/shapintv/talkjs.svg?style=flat-square)](https://travis-ci.com/shapintv/talkjs)
[![Total downloads](https://img.shields.io/packagist/dt/shapin/talkjs.svg?style=flat-square)](https://packagist.org/packages/shapin/talkjs)

## Note: This package is unmaintained. For further updates please see [carandclassic/talkjs](https://github.com/carandclassic/talkjs) 

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

## IDs

TalkJS IDs for users and conversations are custom and managed by your application.

## Filtering

All endpoints that fetch multiple records (users, conversations, messages) have limit & pagination options. API usage below will use a $filters variable where possible for demonstration, and it will look like this:

```php
$filters = [
    'limit' => 50,
    'startingAfter' => 'latestMessageId'
];
```

### Users

Please note TalkJS currently does not offer a user deletion API, and instead [recommend](https://talkjs.com/dashboard/tLjeWrEK/docs/Reference/REST_API/Users.html#page_Deleting-users) you use the update/edit endpoints to anonymise personally identifiable information.

```php
// Create or update a user
$client->users()->createOrUpdate('my_custom_id', [
    'email' => 'georges@abitbol.com',
]);

// Retrieve a user
$user = $client->users()->get('my_custom_id');
```

### Conversations

```php
// Create or update a conversation
$client->conversations()->createOrUpdate('my_custom_id', [
    'subject' => 'My new conversation',
    'participants' => ['my_user_id_1', 'my_user_id_2'],
    'welcomeMessages' => ['Welcome!'],
    'custom' => ['test' => 'test'],
    'photoUrl' => null
]);

// Retrieve a conversation
$conversation = $client->conversations()->get('my_custom_id');

// Find conversations
$conversations = $client->conversations()->find($filters);

// Join a conversation
$client->conversation()->join('my_conversation_id', 'my_user_id');

// Leave a conversation
$client->conversation()->leave('my_conversation_id', 'my_user_id');

// Update participation settings
$params = [
  'notify' => true, // Boolean, default true
  'access' => 'ReadWrite' // ReadWrite or Read, default ReadWrite 
];
$client->conversations()->updateParticipation('my_conversation_id', 'my_user_id', $params);
```

## Messages

For more information on custom data and filters, please refer to the TalkJS documentation linked above.

Please note:

- Sending file attachment is not yet implemented.
- Endpoints that return multiple messages will return them in descending order, i.e. latest first.


```php
$custom = [
  'foo' => 'bar'
];
// Find Messages
$client->conversations()->findMessages('my_conversation_id', $filters);

// Post a system message
$client->conversations()->postSystemMessage('my_conversation_id', 'message_text', $custom);

// Post a user message
$client->conversations()->postUserMessage('my_conversation_id', 'my_user_id', 'message_text', $custom);
```

### Integration with Symfony

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

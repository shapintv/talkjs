<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Api\UserApi;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TalkJSClient
{
    private HttpClientInterface $httpClient;

    public function __construct(string $appId, string $secretKey)
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => 'https://api.talkjs.com/v1/'.$appId.'/',
            'auth_bearer' => $secretKey,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function users(): UserApi
    {
        return new UserApi($this->httpClient);
    }

    public function conversations(): ConversationApi
    {
        return new ConversationApi($this->httpClient);
    }
}

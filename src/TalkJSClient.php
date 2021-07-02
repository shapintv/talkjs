<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS;

use CarAndClassic\TalkJS\Hydrator\ModelHydrator;
use Symfony\Component\HttpClient\HttpClient;

final class TalkJSClient
{
    private $httpClient;
    private $hydrator;

    public function __construct(string $appId, string $secretKey)
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => 'https://api.talkjs.com/v1/'.$appId.'/',
            'auth_bearer' => $secretKey,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->hydrator = new ModelHydrator();
    }

    public function users(): Api\UserApi
    {
        return new Api\UserApi($this->httpClient, $this->hydrator);
    }

    public function conversations(): Api\ConversationApi
    {
        return new Api\ConversationApi($this->httpClient, $this->hydrator);
    }
}

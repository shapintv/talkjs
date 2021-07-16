<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS;

use Shapin\TalkJS\Api\ConversationApi;
use Shapin\TalkJS\Api\UserApi;
use Shapin\TalkJS\Hydrator\Hydrator;
use Shapin\TalkJS\Hydrator\ModelHydrator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TalkJSClient
{
    private HttpClientInterface $httpClient;

    private $hydrator;

    private UserApi $userApi;

    private ConversationApi $conversationApi;

    public function __construct(HttpClientInterface $talkjsClient, Hydrator $hydrator = null)
    {
        $this->httpClient = $talkjsClient;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    public function users(): UserApi
    {
        if (!isset($this->userApi)){
            $this->userApi = new UserApi($this->httpClient, $this->hydrator);
        }
        return $this->userApi;
    }

    public function conversations(): ConversationApi
    {
        if (!isset($this->userApi)){
            $this->conversationApi = new ConversationApi($this->httpClient, $this->hydrator);
        }
        return $this->conversationApi;
    }
}

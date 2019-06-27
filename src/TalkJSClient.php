<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS;

use Shapin\TalkJS\Hydrator\ModelHydrator;
use Shapin\TalkJS\Hydrator\Hydrator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class TalkJSClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    public function __construct(HttpClientInterface $talkjsClient, Hydrator $hydrator = null)
    {
        $this->httpClient = $talkjsClient;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    public function users(): Api\User
    {
        return new Api\User($this->httpClient, $this->hydrator);
    }

    public function conversations(): Api\Conversation
    {
        return new Api\Conversation($this->httpClient, $this->hydrator);
    }
}

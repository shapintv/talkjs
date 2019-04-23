<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS;

use Shapin\TalkJS\Hydrator\ModelHydrator;
use Shapin\TalkJS\Hydrator\Hydrator;
use Http\Client\HttpClient;

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

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * The constructor accepts already configured HTTP clients.
     * Use the configure method to pass a configuration to the Client and create an HTTP Client.
     */
    public function __construct(
        HttpClient $httpClient,
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $this->httpClient = $httpClient;
        $this->hydrator = $hydrator ?: new ModelHydrator();
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
    }

    public static function configure(
        HttpClientConfigurator $httpClientConfigurator,
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ): self {
        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new self($httpClient, $hydrator, $requestBuilder);
    }

    public static function create(string $secretKey, string $appId): self
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($secretKey)
            ->setAppId($appId)
        ;

        return self::configure($httpClientConfigurator);
    }

    public function users(): Api\User
    {
        return new Api\User($this->httpClient, $this->hydrator, $this->requestBuilder);
    }

    public function conversations(): Api\Conversation
    {
        return new Api\Conversation($this->httpClient, $this->hydrator, $this->requestBuilder);
    }
}

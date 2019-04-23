<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS;

use Http\Client\HttpClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\UriFactory;
use Http\Client\Common\Plugin;
use GuzzleHttp\Psr7\Uri;

/**
 * Configure an HTTP client.
 *
 * @internal this class should not be used outside of the API Client, it is not part of the BC promise
 */
final class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint = 'https://api.talkjs.com/';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Plugin[]
     */
    private $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private $appendPlugins = [];

    public function __construct(HttpClient $httpClient = null, UriFactory $uriFactory = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
    }

    public function createConfiguredClient(): HttpClient
    {
        $plugins = $this->prependPlugins;

        $plugins[] = new Plugin\AddHostPlugin($this->uriFactory->createUri($this->endpoint));
        $plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'Shapin/TalkJS (https://github.com/shapintv/talkjs)',
            'Content-Type' => 'application/json',
        ]);

        if (null !== $this->secretKey) {
            $plugins[] = new Plugin\AuthenticationPlugin(new Authentication\Bearer($this->secretKey));
        }
        if (null !== $this->appId) {
            $plugins[] = new Plugin\AddPathPlugin(new Uri('/v1/'.$this->appId));
        }

        return new PluginClient($this->httpClient, array_merge($plugins, $this->appendPlugins));
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setAppId(string $appId): self
    {
        $this->appId = $appId;

        return $this;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    public function appendPlugin(Plugin ...$plugin): self
    {
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }

        return $this;
    }

    public function prependPlugin(Plugin ...$plugin): self
    {
        $plugin = array_reverse($plugin);
        foreach ($plugin as $p) {
            array_unshift($this->prependPlugins, $p);
        }

        return $this;
    }
}

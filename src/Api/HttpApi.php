<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Api;

use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Shapin\TalkJS\Exception\Domain as DomainExceptions;
use Shapin\TalkJS\Exception\DomainException;
use Shapin\TalkJS\Exception\LogicException;
use Shapin\TalkJS\Hydrator\Hydrator;
use Shapin\TalkJS\RequestBuilder;

abstract class HttpApi
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var RequestBuilder
     */
    protected $requestBuilder;

    public function __construct(HttpClient $httpClient, Hydrator $hydrator, RequestBuilder $requestBuilder)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        $this->hydrator = $hydrator;
    }

    /**
     * Send a GET request with query parameters.
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        if (\count($params) > 0) {
            $path .= '?'.http_build_query($params);
        }

        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('GET', $path, $requestHeaders)
        );
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     */
    protected function httpPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
        );
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('PUT', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     */
    protected function httpDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(
            $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createJsonBody($params))
        );
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @throws LogicException
     */
    private function createJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
            return null;
        }

        $body = json_encode($params);

        if (!is_string($body)) {
            throw new LogicException('An error occured when encoding body: '.json_last_error_msg());
        }

        return $body;
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws DomainException
     */
    protected function handleErrors(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 400:
                throw new DomainExceptions\BadRequestException($response);
            case 401:
                throw new DomainExceptions\UnauthorizedException();
            case 404:
                throw new DomainExceptions\NotFoundException();
            case 429:
                throw new DomainExceptions\TooManyRequestsException();
            default:
                throw new DomainExceptions\UnknownErrorException();
        }
    }
}

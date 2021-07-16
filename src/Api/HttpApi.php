<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Api;

use Shapin\TalkJS\Exception\Api\BadRequestException;
use Shapin\TalkJS\Exception\Api\NotFoundException;
use Shapin\TalkJS\Exception\Api\TooManyRequestsException;
use Shapin\TalkJS\Exception\Api\UnauthorizedException;
use Shapin\TalkJS\Exception\Api\UnknownErrorException;
use Shapin\TalkJS\Hydrator\Hydrator;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class HttpApi
{
    protected HttpClientInterface $httpClient;

    protected Hydrator $hydrator;

    public function __construct(HttpClientInterface $httpClient, Hydrator $hydrator)
    {
        $this->httpClient = $httpClient;
        $this->hydrator = $hydrator;
    }

    /**
     * Send a GET request with query parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $path, [
            'query' => $params,
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     * @throws TransportExceptionInterface
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->request('POST', $path, [
            'body' => $body,
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->request('PUT', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpPatch(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->request('PATCH', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     * @throws TransportExceptionInterface
     */
    protected function httpDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpClient->request('DELETE', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @throws \LogicException
     */
    private function createJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
            return null;
        }

        $body = json_encode($params);

        if (!\is_string($body)) {
            throw new \LogicException('An error occured when encoding body: '.json_last_error_msg());
        }

        return $body;
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws BadRequestException
     * @throws UnauthorizedException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnknownErrorException
     * @throws TransportExceptionInterface
     */
    protected function handleErrors(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 400:
                throw new BadRequestException($response);
            case 401:
                throw new UnauthorizedException();
            case 404:
                throw new NotFoundException();
            case 429:
                throw new TooManyRequestsException();
            default:
                throw new UnknownErrorException($response);
        }
    }
}

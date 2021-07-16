<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Api;

use Shapin\TalkJS\Exception;
use Shapin\TalkJS\Exception\Api\ApiException;
use Shapin\TalkJS\Model;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class UserApi extends HttpApi
{
    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->httpPut("users/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\User\UserCreatedOrUpdated::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function get(string $id)
    {
        $response = $this->httpGet("users/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\User\User::class);
    }
}

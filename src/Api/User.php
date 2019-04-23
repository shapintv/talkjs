<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Api;

use Shapin\TalkJS\Configuration;
use Shapin\TalkJS\Exception;
use Shapin\TalkJS\Model;
use Symfony\Component\Config\Definition\Processor;

final class User extends HttpApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->httpPut("/users/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\User\UserCreatedOrUpdated::class);
    }
}

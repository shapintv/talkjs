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

final class Conversation extends HttpApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        if (!array_key_exists('participants', $params) || 0 === count($params['participants'])) {
            throw new Exception\InvalidArgumentException('You need to specify at least 1 user when creating or updateing a conversation.');
        }

        $response = $this->httpPut("/conversations/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\ConversationCreatedOrUpdated::class);
    }

    /**
     * @throws Exception
     */
    public function get(string $id)
    {
        $response = $this->httpGet("/conversations/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\Conversation::class);
    }
}

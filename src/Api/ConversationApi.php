<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJs\Api;

use CarAndClassic\TalkJs\Exception;
use CarAndClassic\TalkJs\Model;

final class ConversationApi extends TalkJsApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->httpPut("conversations/$id", $params);

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
        $response = $this->httpGet("conversations/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\Conversation::class);
    }

    /**
     * @throws Exception
     */
    public function find(array $filters = []): Model\Conversation\ConversationCollection
    {
        $response = $this->httpGet('conversations', $filters);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\ConversationCollection::class);
    }

    /**
     * @throws Exception
     */
    public function join(string $conversationId, string $userId, array $params = [])
    {
        $response = $this->httpPut("conversations/$conversationId/participants/$userId", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\ConversationJoined::class);
    }

    /**
     * @throws Exception
     */
    public function updateParticipation(string $conversationId, string $userId, array $params = [])
    {
        $response = $this->httpPatch("conversations/$conversationId/participants/$userId", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\ParticipationUpdated::class);
    }

    /**
     * @throws Exception
     */
    public function leave(string $conversationId, string $userId)
    {
        $response = $this->httpDelete("conversations/$conversationId/participants/$userId");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\ConversationLeft::class);
    }

    /**
     * @throws Exception
     */
    public function findMessages(string $conversationId, array $filters = []): Model\Conversation\MessageCollection
    {
        $response = $this->httpGet("conversations/$conversationId/messages", $filters);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\MessageCollection::class);
    }

    /**
     * @throws Exception
     */
    public function postSystemMessage(string $conversationId, string $text, array $custom = []): Model\Conversation\MessageCreated
    {
        $response = $this->httpPost("conversations/$conversationId/messages", [
            [
                'type' => 'SystemMessage',
                'text' => $text,
                'custom' => (object) $custom,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\MessageCreated::class);
    }

    /**
     * @throws Exception
     */
    public function postUserMessage(string $conversationId, string $sender, string $text, array $custom = []): Model\Conversation\MessageCreated
    {
        $response = $this->httpPost("conversations/$conversationId/messages", [
            [
                'type' => 'UserMessage',
                'sender' => $sender,
                'text' => $text,
                'custom' => (object) $custom,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Conversation\MessageCreated::class);
    }
}

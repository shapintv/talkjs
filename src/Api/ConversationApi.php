<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Api;

use Shapin\TalkJS\Exception\Api\ApiException;
use Shapin\TalkJS\Model\Conversation\Conversation;
use Shapin\TalkJS\Model\Conversation\ConversationCollection;
use Shapin\TalkJS\Model\Conversation\ConversationCreatedOrUpdated;
use Shapin\TalkJS\Model\Conversation\ConversationJoined;
use Shapin\TalkJS\Model\Conversation\ConversationLeft;
use Shapin\TalkJS\Model\Conversation\MessageCollection;
use Shapin\TalkJS\Model\Conversation\MessageCreated;
use Shapin\TalkJS\Model\Conversation\ParticipationUpdated;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ConversationApi extends HttpApi
{
    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function createOrUpdate(string $id, array $params): ConversationCreatedOrUpdated
    {
        $response = $this->httpPut("conversations/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ConversationCreatedOrUpdated::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function get(string $id): Conversation
    {
        $response = $this->httpGet("conversations/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Conversation::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function find(array $filters = []): ConversationCollection
    {
        $response = $this->httpGet('conversations', $filters);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ConversationCollection::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function join(string $conversationId, string $userId, array $params = []): ConversationJoined
    {
        $response = $this->httpPut("conversations/$conversationId/participants/$userId", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ConversationJoined::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function updateParticipation(string $conversationId, string $userId, array $params = []): ParticipationUpdated
    {
        $response = $this->httpPatch("conversations/$conversationId/participants/$userId", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ParticipationUpdated::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function leave(string $conversationId, string $userId): ConversationLeft
    {
        $response = $this->httpDelete("conversations/$conversationId/participants/$userId");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, ConversationLeft::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function findMessages(string $conversationId, array $filters = []): MessageCollection
    {
        $response = $this->httpGet("conversations/$conversationId/messages", $filters);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, MessageCollection::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function postSystemMessage(string $conversationId, string $text, array $custom = []): MessageCreated
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

        return $this->hydrator->hydrate($response, MessageCreated::class);
    }

    /**
     * @throws ApiException|TransportExceptionInterface
     */
    public function postUserMessage(string $conversationId, string $sender, string $text, array $custom = []): MessageCreated
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

        return $this->hydrator->hydrate($response, MessageCreated::class);
    }
}

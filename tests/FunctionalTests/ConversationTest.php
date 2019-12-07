<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Tests\FunctionalTests;

use Shapin\TalkJS\Model\Conversation\Conversation;
use Shapin\TalkJS\Model\Conversation\ConversationCollection;
use Shapin\TalkJS\Model\Conversation\ConversationCreatedOrUpdated;
use Shapin\TalkJS\Model\Conversation\ConversationJoined;
use Shapin\TalkJS\Model\Conversation\ConversationLeft;
use Shapin\TalkJS\Model\Conversation\ParticipationUpdated;

final class ConversationTest extends TestCase
{
    private $api;

    public function setUp(): void
    {
        $this->api = $this->getTalkJSClient()->conversations();
    }

    public function testCreateOrUpdate()
    {
        $randomTestString = bin2hex(random_bytes(10));
        $conversationId = "conversation_$randomTestString";

        $response = $this->api->createOrUpdate($conversationId, [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
            'welcomeMessages' => ['Hello', 'World'],
            'photoUrl' => 'photo_url',
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);

        $this->assertSame($conversationId, $conversation->getId());
        $this->assertSame('An amazing conversation', $conversation->getSubject());
        $this->assertNull($conversation->getTopicId());
        $this->assertSame('photo_url', $conversation->getPhotoUrl());
        $this->assertSame(['Hello', 'World'], $conversation->getWelcomeMessages());
        $custom = $conversation->getCustom();
        $this->assertFalse(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertSame(['my_user' => ['notify' => true, 'access' => 'ReadWrite']], $conversation->getParticipants());
        $this->assertInstanceOf(\DateTimeImmutable::class, $conversation->getCreatedAt());

        $response = $this->api->createOrUpdate($conversationId, [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
            'custom' => [
                'test' => $randomTestString,
            ],
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);

        $this->assertSame($conversationId, $conversation->getId());
        $custom = $conversation->getCustom();
        $this->assertTrue(isset($custom['test']) && $randomTestString === $custom['test']);

        $collection = $this->api->find(['limit' => 50]);
        $this->assertInstanceOf(ConversationCollection::class, $collection);
        $this->assertTrue($collection->contains($conversationId));

        // Delete my_user from participants
        $conversationLeft = $this->api->leave($conversationId, 'my_user');
        $this->assertInstanceOf(ConversationLeft::class, $conversationLeft);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame([], $conversation->getParticipants());

        // Add user back in participants
        $conversationJoined = $this->api->join($conversationId, 'my_user');
        $this->assertInstanceOf(ConversationJoined::class, $conversationJoined);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(['my_user' => ['notify' => true, 'access' => 'ReadWrite']], $conversation->getParticipants());

        // Modify participation
        $participationUpdated = $this->api->updateParticipation($conversationId, 'my_user', ['notify' => false, 'access' => 'Read']);
        $this->assertInstanceOf(ParticipationUpdated::class, $participationUpdated);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(['my_user' => ['notify' => false, 'access' => 'Read']], $conversation->getParticipants());
    }
}

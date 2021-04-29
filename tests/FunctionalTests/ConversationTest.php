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
use Shapin\TalkJS\Model\Conversation\Message;
use Shapin\TalkJS\Model\Conversation\MessageCollection;
use Shapin\TalkJS\Model\Conversation\MessageCreated;
use Shapin\TalkJS\Model\Conversation\ParticipationUpdated;

final class ConversationTest extends TestCase
{
    private $api;

    protected function setUp(): void
    {
        $this->api = $this->getTalkJSClient()->conversations();
    }

    public function testAll()
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
        $this->assertSame(['my_user' => ['access' => 'ReadWrite', 'notify' => true]], $conversation->getParticipants());
        $this->assertInstanceOf(\DateTimeImmutable::class, $conversation->getCreatedAt());

        $response = $this->api->createOrUpdate($conversationId, [
            'subject' => 'An amazing conversation!',
            'welcomeMessages' => ['Hello', 'World!'],
            'photoUrl' => 'another_photo_url',
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
        $this->assertEquals('An amazing conversation!', $conversation->getSubject());
        $this->assertEquals(['Hello', 'World!'], $conversation->getWelcomeMessages());
        $this->assertEquals('another_photo_url', $conversation->getPhotoUrl());

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
        $this->assertSame(['my_user' => ['access' => 'ReadWrite', 'notify' => true]], $conversation->getParticipants());

        // Modify participation
        $participationUpdated = $this->api->updateParticipation($conversationId, 'my_user', ['notify' => false, 'access' => 'Read']);
        $this->assertInstanceOf(ParticipationUpdated::class, $participationUpdated);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(['my_user' => ['access' => 'Read', 'notify' => false]], $conversation->getParticipants());

        // Find messages: none should be found.
        $messages = $this->api->findMessages($conversationId);
        $this->assertInstanceOf(MessageCollection::class, $messages);
        $this->assertCount(0, $messages);

        // Post a new system message.
        $messageCreated = $this->api->postSystemMessage($conversationId, 'An amazing system message', ['foo' => 'bar']);
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);

        // We now have 1 available system message
        $messages = $this->api->findMessages($conversationId);
        $this->assertInstanceOf(MessageCollection::class, $messages);
        $this->assertCount(1, $messages);
        $message = $messages->getIterator()->current();
        $this->assertInstanceOf(Message::class, $message);
        $this->assertTrue($message->isSystemMessage());
        $this->assertSame('An amazing system message', $message->getText());
        $this->assertNull($message->getSenderId());
        $this->assertCount(0, $message->getReadBy());
        $this->assertSame('rest', $message->getOrigin());
        $this->assertNull($message->getLocation());
        $this->assertSame(['foo' => 'bar'], $message->getCustom());
        $this->assertSame($conversationId, $message->getConversationId());
        $this->assertNull($message->getAttachment());

        // Post a new user message.
        $messageCreated = $this->api->postUserMessage($conversationId, 'my_user', 'An amazing user message');
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);

        // We now have 2 available messages
        $messages = $this->api->findMessages($conversationId);
        $this->assertInstanceOf(MessageCollection::class, $messages);
        $this->assertCount(2, $messages);
        $message = $messages->getIterator()->current();
        $this->assertInstanceOf(Message::class, $message);
        $this->assertTrue($message->isUserMessage());
        $this->assertSame('An amazing user message', $message->getText());
        $this->assertSame('my_user', $message->getSenderId());
        $this->assertCount(0, $message->getReadBy());
        $this->assertSame('rest', $message->getOrigin());
        $this->assertNull($message->getLocation());
        $this->assertSame([], $message->getCustom());
        $this->assertSame($conversationId, $message->getConversationId());
        $this->assertNull($message->getAttachment());
    }
}

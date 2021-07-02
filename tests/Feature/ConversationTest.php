<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\ConversationApi;
use CarAndClassic\TalkJS\Models\Conversation;
use CarAndClassic\TalkJS\Models\ConversationCreatedOrUpdated;
use CarAndClassic\TalkJS\Models\ConversationJoined;
use CarAndClassic\TalkJS\Models\ConversationLeft;
use CarAndClassic\TalkJS\Models\Message;
use CarAndClassic\TalkJS\Models\MessageCreated;
use CarAndClassic\TalkJS\Models\ParticipationUpdated;

final class ConversationTest extends TestCase
{
    private ConversationApi $api;

    protected function setUp(): void
    {
        $this->api = $this->getTalkJSClient()->conversationApi;
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

        $this->assertSame($conversationId, $conversation->id);
        $this->assertSame('An amazing conversation', $conversation->subject);
        $this->assertNull($conversation->topicId);
        $this->assertSame('photo_url', $conversation->photoUrl);
        $this->assertSame(['Hello', 'World'], $conversation->welcomeMessages);
        $custom = $conversation->custom;
        $this->assertFalse(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertSame(['my_user' => ['access' => 'ReadWrite', 'notify' => true]], $conversation->participants);
        $this->assertInstanceOf(\DateTimeImmutable::class, $conversation->createdAt);

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

        $this->assertSame($conversationId, $conversation->id);
        $custom = $conversation->custom;
        $this->assertTrue(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertEquals('An amazing conversation!', $conversation->subject);
        $this->assertEquals(['Hello', 'World!'], $conversation->welcomeMessages);
        $this->assertEquals('another_photo_url', $conversation->photoUrl);

        $collection = $this->api->find(['limit' => 50]);
        $this->assertIsArray($collection);
        $this->assertTrue($collection->contains($conversationId));

        // Delete my_user from participants
        $conversationLeft = $this->api->leave($conversationId, 'my_user');
        $this->assertInstanceOf(ConversationLeft::class, $conversationLeft);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame([], $conversation->participants);

        // Add user back in participants
        $conversationJoined = $this->api->join($conversationId, 'my_user');
        $this->assertInstanceOf(ConversationJoined::class, $conversationJoined);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(['my_user' => ['access' => 'ReadWrite', 'notify' => true]], $conversation->participants);

        // Modify participation
        $participationUpdated = $this->api->updateParticipation($conversationId, 'my_user', ['notify' => false, 'access' => 'Read']);
        $this->assertInstanceOf(ParticipationUpdated::class, $participationUpdated);

        $conversation = $this->api->get($conversationId);
        $this->assertInstanceOf(Conversation::class, $conversation);
        $this->assertSame(['my_user' => ['access' => 'Read', 'notify' => false]], $conversation->participants);

        // Find messages: none should be found.
        $messages = $this->api->findMessages($conversationId);
        $this->assertIsArray($messages);
        $this->assertCount(0, $messages);

        // Post a new system message.
        $messageCreated = $this->api->postSystemMessage($conversationId, 'An amazing system message', ['foo' => 'bar']);
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);

        // We now have 1 available system message
        $messages = $this->api->findMessages($conversationId);
        $this->assertIsArray($messages);
        $this->assertCount(1, $messages);
        $message = $messages[0];
        $this->assertInstanceOf(Message::class, $message);
        $this->assertTrue($message->isSystemMessage());
        $this->assertSame('An amazing system message', $message->text);
        $this->assertNull($message->senderId);
        $this->assertCount(0, $message->readBy);
        $this->assertSame('rest', $message->origin);
        $this->assertNull($message->location);
        $this->assertSame(['foo' => 'bar'], $message->custom);
        $this->assertSame($conversationId, $message->conversationId);
        $this->assertNull($message->attachment);

        // Post a new user message.
        $messageCreated = $this->api->postUserMessage($conversationId, 'my_user', 'An amazing user message');
        $this->assertInstanceOf(MessageCreated::class, $messageCreated);

        // We now have 2 available messages
        $messages = $this->api->findMessages($conversationId);
        $this->assertIsArray($messages);
        $this->assertCount(2, $messages);
        $message = $messages[0];
        $this->assertInstanceOf(Message::class, $message);
        $this->assertTrue($message->isUserMessage());
        $this->assertSame('An amazing user message', $message->text);
        $this->assertSame('my_user', $message->senderId);
        $this->assertCount(0, $message->readBy);
        $this->assertSame('rest', $message->origin);
        $this->assertNull($message->location);
        $this->assertSame([], $message->custom);
        $this->assertSame($conversationId, $message->conversationId);
        $this->assertNull($message->attachment);
    }
}

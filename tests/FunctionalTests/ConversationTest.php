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

        $response = $this->api->createOrUpdate('my_conversation', [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
            'welcomeMessages' => ['Hello', 'World'],
            'photoUrl' => 'photo_url',
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);

        $conversation = $this->api->get('my_conversation');
        $this->assertInstanceOf(Conversation::class, $conversation);

        $this->assertSame('my_conversation', $conversation->getId());
        $this->assertSame('An amazing conversation', $conversation->getSubject());
        $this->assertNull($conversation->getTopicId());
        $this->assertSame('photo_url', $conversation->getPhotoUrl());
        $this->assertSame(['Hello', 'World'], $conversation->getWelcomeMessages());
        $custom = $conversation->getCustom();
        $this->assertFalse(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertSame(['my_user' => ['notify' => true, 'access' => 'ReadWrite']], $conversation->getParticipants());
        $this->assertInstanceOf(\DateTimeImmutable::class, $conversation->getCreatedAt());

        $response = $this->api->createOrUpdate('my_conversation', [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
            'custom' => [
                'test' => $randomTestString,
            ],
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);

        $conversation = $this->api->get('my_conversation');
        $this->assertInstanceOf(Conversation::class, $conversation);

        $this->assertSame('my_conversation', $conversation->getId());
        $custom = $conversation->getCustom();
        $this->assertTrue(isset($custom['test']) && $randomTestString === $custom['test']);

        $collection = $this->api->find(['limit' => 50]);
        $this->assertInstanceOf(ConversationCollection::class, $collection);
        $this->assertTrue($collection->contains('my_conversation'));
    }
}

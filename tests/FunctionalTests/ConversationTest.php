<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Tests\FunctionalTests;

use Shapin\TalkJS\Exception\Domain\NotFoundException;
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
        $response = $this->api->createOrUpdate('my_conversation', [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);

        $response = $this->api->createOrUpdate('my_conversation', [
            'participants' => ['my_user'],
            'subject' => 'An amazing conversation',
            'custom' => [
                'test' => 'coucou',
            ],
        ]);

        $this->assertInstanceOf(ConversationCreatedOrUpdated::class, $response);
    }
}

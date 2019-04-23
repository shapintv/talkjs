<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Tests\FunctionalTests;

use Shapin\TalkJS\Exception\Domain\NotFoundException;
use Shapin\TalkJS\Model\User\UserCreatedOrUpdated;

final class UserTest extends TestCase
{
    private $api;

    public function setUp(): void
    {
        $this->api = $this->getTalkJSClient()->users();
    }

    public function testCreateOrUpdate()
    {
        $response = $this->api->createOrUpdate('my_user', [
            'name' => 'Georges Abitbol',
            'email' => ['georges@abitbol.fr'],
        ]);

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $response);

        $response = $this->api->createOrUpdate('my_user', [
            'name' => 'Georges Abitbol',
            'custom' => [
                'test' => 'coucou',
            ],
        ]);

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $response);
    }
}

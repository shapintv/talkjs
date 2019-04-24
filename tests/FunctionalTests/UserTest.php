<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Tests\FunctionalTests;

use Shapin\TalkJS\Exception\Domain\NotFoundException;
use Shapin\TalkJS\Model\User\User;
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
        $randomTestString = bin2hex(random_bytes(10));

        $response = $this->api->createOrUpdate('my_user', [
            'name' => 'Georges Abitbol',
            'email' => ['georges@abitbol.fr'],
            'welcomeMessage' => 'welcome',
            'photoUrl' => 'photo_url',
            'role' => 'role',
            'locale' => 'fr',
        ]);

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $response);

        $user = $this->api->get('my_user');
        $this->assertInstanceOf(User::class, $user);

        $this->assertSame('my_user', $user->getId());
        $this->assertSame('Georges Abitbol', $user->getName());
        $this->assertSame('welcome', $user->getWelcomeMessage());
        $this->assertSame('photo_url', $user->getPhotoUrl());
        $this->assertSame('role', $user->getRole());
        $this->assertSame(['georges@abitbol.fr'], $user->getEmail());
        $this->assertSame([], $user->getPhone());
        $custom = $user->getCustom();
        $this->assertFalse(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertNull($user->getAvailabilityText());
        $this->assertSame('fr', $user->getLocale());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());

        $response = $this->api->createOrUpdate('my_user', [
            'name' => 'Georges Abitbol',
            'custom' => [
                'test' => $randomTestString,
            ],
        ]);

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $response);

        $user = $this->api->get('my_user');
        $this->assertInstanceOf(User::class, $user);

        $this->assertSame('my_user', $user->getId());
        $custom = $user->getCustom();
        $this->assertTrue(isset($custom['test']) && $randomTestString === $custom['test']);
    }
}

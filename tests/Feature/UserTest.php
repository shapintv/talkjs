<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Feature;

use CarAndClassic\TalkJS\Api\UserApi;
use CarAndClassic\TalkJS\Models\User;
use CarAndClassic\TalkJS\Models\UserCreatedOrUpdated;

final class UserTest extends TestCase
{
    private UserApi $api;

    protected function setUp(): void
    {
        $this->api = $this->getTalkJSClient()->userApi;
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

        $this->assertSame('my_user', $user->id);
        $this->assertSame('Georges Abitbol', $user->name);
        $this->assertSame('welcome', $user->welcomeMessage);
        $this->assertSame('photo_url', $user->photoUrl);
        $this->assertSame('role', $user->role);
        $this->assertSame(['georges@abitbol.fr'], $user->email);
        $this->assertSame([], $user->phone);
        $custom = $user->custom;
        $this->assertFalse(isset($custom['test']) && $randomTestString === $custom['test']);
        $this->assertNull($user->availabilityText);
        $this->assertSame('fr', $user->locale);
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->createdAt);

        $response = $this->api->createOrUpdate('my_user', [
            'name' => 'Georges Abitbol',
            'custom' => [
                'test' => $randomTestString,
            ],
        ]);

        $this->assertInstanceOf(UserCreatedOrUpdated::class, $response);

        $user = $this->api->get('my_user');
        $this->assertInstanceOf(User::class, $user);

        $this->assertSame('my_user', $user->id);
        $custom = $user->custom;
        $this->assertTrue(isset($custom['test']) && $randomTestString === $custom['test']);
    }
}

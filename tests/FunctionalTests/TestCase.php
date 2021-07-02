<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJs\Tests\FunctionalTests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use CarAndClassic\TalkJs\TalkJsClient;
use Symfony\Component\HttpClient\HttpClient;

abstract class TestCase extends BaseTestCase
{
    const APP_ID = 'toQncizZ';
    const SECRET_KEY = 'sk_test_3y3JDebNoA4mUhRUD7IBM8b7';

    public function getTalkJsClient()
    {
        $httpClient = HttpClient::create([
            'base_uri' => 'https://api.talkjs.com/v1/'.self::APP_ID.'/',
            'auth_bearer' => self::SECRET_KEY,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        return new TalkJsClient($httpClient);
    }
}

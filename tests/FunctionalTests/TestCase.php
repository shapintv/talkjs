<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Tests\FunctionalTests;

use Shapin\TalkJS\HttpClientConfigurator;
use Shapin\TalkJS\TalkJSClient;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    const APP_ID = 'toQncizZ';
    const SECRET_KEY = 'sk_test_3y3JDebNoA4mUhRUD7IBM8b7';

    public function getTalkJSClient()
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setSecretKey(self::SECRET_KEY)
            ->setAppId(self::APP_ID)
        ;

        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new TalkJSClient($httpClient);
    }
}

<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Tests\Feature;

use PHPUnit\Framework\TestCase as BaseTestCase;
use CarAndClassic\TalkJS\TalkJSClient;

abstract class TestCase extends BaseTestCase
{
    public function getTalkJSClient(): TalkJSClient
    {
        $appId = '';
        $secretKey = '';
        return new TalkJSClient($appId, $secretKey);
    }
}

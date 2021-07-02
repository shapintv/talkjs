<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Exception\Domain;

use CarAndClassic\TalkJS\Exception\DomainException;

final class TooManyRequestsException extends \Exception implements DomainException
{
}

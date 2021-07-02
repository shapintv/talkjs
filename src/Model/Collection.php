<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJs\Model;

abstract class Collection extends \ArrayObject implements CreatableFromArray
{
    public function contains(string $key): bool
    {
        return isset($this[$key]);
    }
}

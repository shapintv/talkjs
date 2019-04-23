<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Hydrator;

use Psr\Http\Message\ResponseInterface;

/**
 * Hydrate a PSR-7 response to something else.
 */
interface Hydrator
{
    /**
     * @return mixed
     */
    public function hydrate(ResponseInterface $response, string $class);
}

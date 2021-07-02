<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJs\Hydrator;

use CarAndClassic\TalkJs\Exception\HydrationException;
use CarAndClassic\TalkJs\Model\CreatableFromArray;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Hydrate an HTTP response to domain object.
 */
final class ModelHydrator implements Hydrator
{
    /**
     * @return mixed
     */
    public function hydrate(ResponseInterface $response, string $class)
    {
        if (!isset($response->getHeaders()['content-type'])) {
            throw new HydrationException('The ModelHydrator cannot hydrate response without Content-Type header.');
        }

        $contentType = reset($response->getHeaders()['content-type']);
        if (0 !== strpos($contentType, 'application/json')) {
            throw new HydrationException("The ModelHydrator cannot hydrate response with Content-Type: $contentType");
        }

        $data = json_decode($response->getContent(), true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        if (is_subclass_of($class, CreatableFromArray::class)) {
            $object = \call_user_func($class.'::createFromArray', $data);
        } else {
            $object = new $class($data);
        }

        return $object;
    }
}

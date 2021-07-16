<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Exception\Api;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class UnknownErrorException extends \Exception implements ApiException
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $content = json_decode($response->getContent(false), true);

        if (!isset($content['reasons'])) {
            parent::__construct('Unknown error: No reason.');

            return;
        }

        $field = array_key_first($content['reasons']);
        $reasons = reset($content['reasons']);
        $reason = \is_array($reasons) ? $reasons[0] : $reasons;

        parent::__construct("Unknown error: Field $field: $reason.");
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

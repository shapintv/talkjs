<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace CarAndClassic\TalkJS\Exceptions\Api;

use Symfony\Contracts\HttpClient\ResponseInterface;

class BadRequestException extends \Exception
{
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $content = json_decode($response->getContent(false), true);

        parent::__construct('Bad request. Content: '.json_encode($content));
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

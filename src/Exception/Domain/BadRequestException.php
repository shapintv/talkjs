<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\TalkJS\Exception\Domain;

use Shapin\TalkJS\Exception\DomainException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BadRequestException extends \Exception implements DomainException
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

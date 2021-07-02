<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

class MessageCreated
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}

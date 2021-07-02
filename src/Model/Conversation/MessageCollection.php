<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJs\Model\Conversation;

use CarAndClassic\TalkJs\Model\Collection;

class MessageCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $messages = [];

        foreach ($data['data'] as $message) {
            $messages[$message['id']] = Message::createFromArray($message);
        }

        return new self($messages);
    }
}

<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJs\Model\Conversation;

use CarAndClassic\TalkJs\Model\Collection;

class ConversationCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $conversations = [];

        foreach ($data['data'] as $conversation) {
            $conversations[$conversation['id']] = Conversation::createFromArray($conversation);
        }

        return new self($conversations);
    }
}

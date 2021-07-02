<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Model\Conversation;

use CarAndClassic\TalkJS\Model\Collection;

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

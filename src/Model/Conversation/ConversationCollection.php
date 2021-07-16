<?php

declare(strict_types=1);

namespace Shapin\TalkJS\Model\Conversation;

use Shapin\TalkJS\Model\Collection;

class ConversationCollection extends Collection
{
    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data): self
    {
        $conversations = [];

        foreach ($data['data'] as $conversation) {
            $conversations[$conversation['id']] = Conversation::createFromArray($conversation);
        }

        return new self($conversations);
    }
}

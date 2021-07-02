<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Models;

use CarAndClassic\TalkJS\Models\CreatableFromArray;

class Message implements CreatableFromArray
{
    const TYPE_SYSTEM_MESSAGE = 'SystemMessage';
    const TYPE_USER_MESSAGE = 'UserMessage';

    public string $type;

    public string $text;

    public string $senderId;

    public array $readBy;

    public string $origin;

    public string $location;

    public string $id;

    public array $custom;

    public string $conversationId;

    public \DateTimeImmutable $createdAt;

    public string $attachment;

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data): self
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $message = new self();
        $message->type = $data['type'];
        $message->text = $data['text'];
        $message->senderId = $data['senderId'];
        $message->readBy = $data['readBy'];
        $message->origin = $data['origin'];
        $message->location = $data['location'];
        $message->id = $data['id'];
        $message->custom = $data['custom'];
        $message->createdAt = new \DateTimeImmutable("@$timestamp");
        $message->conversationId = $data['conversationId'];
        $message->attachment = $data['attachment'];

        return $message;
    }

    public static function createManyFromArray(array $data): array
    {
        $messages = [];

        foreach ($data as $message) {
            $messages[$message['id']] = self::createFromArray($message);
        }

        return $messages;
    }
}

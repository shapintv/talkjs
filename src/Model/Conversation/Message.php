<?php

declare(strict_types=1);

namespace Shapin\TalkJS\Model\Conversation;

use Shapin\TalkJS\Model\CreatableFromArray;

class Message implements CreatableFromArray
{
    const TYPE_SYSTEM_MESSAGE = 'SystemMessage';
    const TYPE_USER_MESSAGE = 'UserMessage';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $text;

    /**
     * @var ?string
     */
    private $senderId;

    /**
     * @var array
     */
    private $readBy;

    /**
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $custom;

    /**
     * @var string
     */
    private $conversationId;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var string
     */
    private $attachment;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function isSystemMessage(): bool
    {
        return self::TYPE_SYSTEM_MESSAGE === $this->type;
    }

    public function isUserMessage(): bool
    {
        return self::TYPE_USER_MESSAGE === $this->type;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getSenderId(): ?string
    {
        return $this->senderId;
    }

    public function getReadBy(): array
    {
        return $this->readBy;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getConversationId(): string
    {
        return $this->conversationId;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }
}

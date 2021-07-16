<?php

declare(strict_types=1);

namespace Shapin\TalkJS\Model\Conversation;

use Shapin\TalkJS\Model\CreatableFromArray;

class Conversation implements CreatableFromArray
{
    private string $id;

    private ?string $subject;

    private ?string $topicId;

    private ?string $photoUrl;

    private array $welcomeMessages;

    private array $custom;

    private array $participants;

    private \DateTimeImmutable $createdAt;

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data): self
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $user = new self();
        $user->id = $data['id'];
        $user->subject = $data['subject'];
        $user->topicId = $data['topicId'];
        $user->photoUrl = $data['photoUrl'];
        $user->welcomeMessages = $data['welcomeMessages'];
        $user->custom = $data['custom'] ?? [];
        $user->participants = $data['participants'] ?? [];
        $user->createdAt = new \DateTimeImmutable("@$timestamp");

        return $user;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getTopicId(): ?string
    {
        return $this->topicId;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function getWelcomeMessages(): array
    {
        return $this->welcomeMessages;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

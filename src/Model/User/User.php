<?php

declare(strict_types=1);

namespace Shapin\TalkJS\Model\User;

use Shapin\TalkJS\Model\CreatableFromArray;

class User implements CreatableFromArray
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ?string
     */
    private $welcomeMessage;

    /**
     * @var ?string
     */
    private $photoUrl;

    /**
     * @var ?string
     */
    private $role;

    /**
     * @var array
     */
    private $email;

    /**
     * @var array
     */
    private $phone;

    /**
     * @var array
     */
    private $custom;

    /**
     * @var ?string
     */
    private $availabilityText;

    /**
     * @var ?string
     */
    private $locale;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * {@inheritdoc}
     */
    public static function createFromArray(array $data)
    {
        $timestamp = round($data['createdAt'] / 1000, 0);

        $user = new self();
        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->welcomeMessage = $data['welcomeMessage'];
        $user->photoUrl = $data['photoUrl'];
        $user->role = $data['role'];
        $user->email = $data['email'] ?? [];
        $user->phone = $data['phone'] ?? [];
        $user->custom = $data['custom'] ?? [];
        $user->availabilityText = $data['availabilityText'];
        $user->locale = $data['locale'];
        $user->createdAt = new \DateTimeImmutable("@$timestamp");

        return $user;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWelcomeMessage(): ?string
    {
        return $this->welcomeMessage;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getEmail(): array
    {
        return $this->email;
    }

    public function getPhone(): array
    {
        return $this->phone;
    }

    public function getCustom(): array
    {
        return $this->custom;
    }

    public function getAvailabilityText(): ?string
    {
        return $this->availabilityText;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}

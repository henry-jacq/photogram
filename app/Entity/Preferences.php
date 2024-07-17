<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'preferences')]
class Preferences
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'string', options: ['default' => 'dark'])]
    private string $theme;

    #[ORM\Column(type: 'json', name: 'notification_settings', nullable: true)]
    private ?array $notificationSettings = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    public function getNotificationSettings(): ?array
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(?array $notificationSettings): self
    {
        $this->notificationSettings = $notificationSettings;
        return $this;
    }
}

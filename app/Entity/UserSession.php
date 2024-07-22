<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity, ORM\Table(name: 'user_sessions')]
class UserSession
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTimeInterface $loginTime;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $ipAddress;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $userAgent;

    #[ORM\Column(type: 'string', length: 64, nullable: false, unique: true)]
    private string $hashKey;

    
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

    public function getLoginTime(): \DateTimeInterface
    {
        return $this->loginTime;
    }

    public function setLoginTime(\DateTimeInterface $loginTime): self
    {
        $this->loginTime = $loginTime;
        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getHashKey(): string
    {
        return $this->hashKey;
    }

    public function setHashKey(string $hashKey): void
    {
        $this->hashKey = $hashKey;
    }
}

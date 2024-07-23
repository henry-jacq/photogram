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
    
    #[ORM\Column(type: 'string', name: 'session_id', nullable: false, unique: true)]
    private string $sessionId;
    
    #[ORM\Column(type: 'string', name: 'session_token', nullable: false)]
    private string $sessionToken;

    #[ORM\Column(type: 'string', name: 'ip_address', nullable: false)]
    private string $ipAddress;
    
    #[ORM\Column(type: 'string', name: 'user_agent', nullable: false)]
    private string $userAgent;
    
    #[ORM\Column(type: 'datetime', name: 'login_time', nullable: false)]
    private \DateTimeInterface $loginTime;

    #[ORM\Column(type: 'datetime', name: 'last_activity', nullable: false)]
    private \DateTimeInterface $lastActivity;


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

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }

    public function setSessionToken(string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;
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

    public function getLastActivity(): \DateTimeInterface
    {
        return $this->lastActivity;
    }

    public function setLastActivity(\DateTimeInterface $lastActivity): self
    {
        $this->lastActivity = $lastActivity;
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

    public function getExpiredAt()
    {
        
    }
}

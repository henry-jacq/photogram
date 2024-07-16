<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table('users')]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $username;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[Column(name: 'created_at')]
    private \DateTime $created_at;

    #[Column(name: 'reset_token')]
    private string $reset_token;

    #[Column(name: 'reset_token_expiry')]
    private \DateTime $reset_token_expiry;

    public function __construct()
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): User
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getResetToken(): string
    {
        return $this->reset_token;
    }

    public function setResetToken(string $reset_token): User
    {
        $this->reset_token = $reset_token;
        return $this;
    }

    public function getResetTokenExpiry(): \DateTime
    {
        return $this->reset_token_expiry;
    }

    public function setResetTokenExpiry(\DateTime $reset_token_expiry): User
    {
        $this->reset_token_expiry = $reset_token_expiry;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'reset_token' => $this->getResetToken(),
            'reset_token_expiry' => $this->getResetTokenExpiry()->format('Y-m-d H:i:s'),
        ];
    }

    public function fromArray(array $data): User
    {
        $this->setUsername($data['username']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setCreatedAt(new \DateTime($data['created_at']));
        $this->setResetToken($data['reset_token']);
        $this->setResetTokenExpiry(new \DateTime($data['reset_token_expiry']));
        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'follows')]
class Follow
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "followers")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private ?User $user;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "following")]
    #[ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'id', nullable: false, onDelete: "CASCADE")]
    private ?User $followUser;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getFollowUser(): ?User
    {
        return $this->followUser;
    }

    public function setFollowUser(?User $followUser): self
    {
        $this->followUser = $followUser;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}

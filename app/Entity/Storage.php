<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'storage')]
class Storage
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'integer', name: 'total_space')]
    private int $totalSpace;

    #[ORM\Column(type: 'integer', name: 'used_space')]
    private int $usedSpace;

    #[ORM\Column(type: 'integer', name: 'remaining_space')]
    private int $remainingSpace;

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

    public function getTotalSpace(): int
    {
        return $this->totalSpace;
    }

    public function setTotalSpace(int $totalSpace): self
    {
        $this->totalSpace = $totalSpace;
        return $this;
    }

    public function getUsedSpace(): int
    {
        return $this->usedSpace;
    }

    public function setUsedSpace(int $usedSpace): self
    {
        $this->usedSpace = $usedSpace;
        return $this;
    }

    public function getRemainingSpace(): int
    {
        return $this->remainingSpace;
    }

    public function setRemainingSpace(int $remainingSpace): self
    {
        $this->remainingSpace = $remainingSpace;
        return $this;
    }
}

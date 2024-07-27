<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;

#[ORM\Entity, ORM\Table(name: 'user_storage')]
class Storage
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, unique: true, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'float', name:'total_space', options: ['unsigned' => true])]
    private float $totalSpace;

    #[ORM\Column(type: 'float', name:'used_space', options: ['unsigned' => true])]
    private float $usedSpace;

    #[ORM\Column(type: 'float', name:'remaining_space', options: ['unsigned' => true])]
    private float $remainingSpace;

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

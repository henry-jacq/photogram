<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $caption = null;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'boolean', name: 'is_archived', options: ['default' => false])]
    private bool $isArchived;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Like::class, cascade: ['persist', 'remove'])]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, cascade: ['persist', 'remove'])]
    private Collection $comments;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getIsArchived(): bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPost($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getPost() === $this) {
                $image->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Get the number of likes for this post
     */
    public function getLikesCount(): int
    {
        return count($this->likes);
    }

    /**
     * Get the users who liked this post
     */
    public function getLikedUsers(): array
    {
        $likedUsers = $this->likes->map(function (Like $like) {
            return $like->getLikedUser();
        });

        return array_reverse($likedUsers->toArray());
    }
    
}

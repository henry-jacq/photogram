<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'likes')]
class Like
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'likes')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Post $post;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'post_owner_id', referencedColumnName: 'id', nullable: false)]
    private ?User $postOwner;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'liked_user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $likedUser;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getPostOwner(): User
    {
        return $this->postOwner;
    }

    public function setPostOwner(?User $postOwner): self
    {
        $this->postOwner = $postOwner;
        return $this;
    }

    public function getLikedUser(): User
    {
        return $this->likedUser;
    }

    public function setLikedUser(?User $likedUser): self
    {
        $this->likedUser = $likedUser;
        return $this;
    }
}

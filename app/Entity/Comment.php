<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'comments')]
class Comment
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id', nullable: false)]
    private Post $post;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'post_owner_id', referencedColumnName: 'id', nullable: false)]
    private User $postOwner;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'comment_user_id', referencedColumnName: 'id', nullable: false)]
    private User $commentUser;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime', name: 'comment_date')]
    private \DateTime $commentDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getPostOwner(): User
    {
        return $this->postOwner;
    }

    public function setPostOwner(User $postOwner): self
    {
        $this->postOwner = $postOwner;
        return $this;
    }

    public function getCommentUser(): User
    {
        return $this->commentUser;
    }

    public function setCommentUser(User $commentUser): self
    {
        $this->commentUser = $commentUser;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCommentDate(): \DateTime
    {
        return $this->commentDate;
    }

    public function setCommentDate(\DateTime $commentDate): self
    {
        $this->commentDate = $commentDate;
        return $this;
    }
}

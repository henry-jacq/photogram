<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Post $post;

    #[ORM\Column(type: 'string', name: 'image_path')]
    private string $imagePath;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }
}

<?php

namespace App\Entity;

use Exception;
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

    // Storage Path
    private string $storage_path = STORAGE_PATH . '/posts/';

    public function __construct()
    {
        if (!file_exists($this->storage_path)) {
            mkdir($this->storage_path);
        }
    }

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

    /**
     * Store the image to the storage folder
     */
    public function saveToStorage(string $image_tmp): string
    {
        if (is_file($image_tmp) && exif_imagetype($image_tmp) !== false) {
            // security through obscurity
            $name = md5(strlen($this->storage_path) * mt_rand(0, 99999) . time());
            $ext = image_type_to_extension(exif_imagetype($image_tmp));
            $imageName = $name . $ext;
            $image_path = $this->storage_path . $imageName;

            if (move_uploaded_file($image_tmp, $image_path)) {
                return $imageName;
            }

            throw new Exception("Can't move the uploaded file");
        } else {
            throw new Exception("Not a valid image path!");
        }
    }
}

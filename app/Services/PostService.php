<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Post;
use App\Entity\Image;
use Doctrine\ORM\EntityManager;


class PostService
{
    public function __construct(private EntityManager $em)
    {
    }
    
    public function createPost(array $data)
    {
        $post = new Post();
        $post->setUser($data['user']); // User Object
        $post->setCaption($data['text']);
        $post->setCreatedAt(new \DateTime());
        $post->setIsArchived(false);

        foreach ($data['images'] as $image_path) {
            $image = new Image();
            $name = $image->saveToStorage($image_path);
            $image->setImagePath($name);
            $post->addImage($image);
        }

        $this->em->persist($post);
        $this->em->flush();

        return true;
    }

    /**
     * Return Post Image Content
     */
    public function getImage(string $imageName)
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . $imageName;

        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Return all posts
     */
    public function fetchAllPosts()
    {
        return $this->em->getRepository(Post::class)->findAll();
    }
}

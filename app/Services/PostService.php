<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\Post;
use App\Entity\Image;
use Doctrine\ORM\EntityManager;


class PostService
{
    // Storage Path for the post images
    private string $storagePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR;

    public function __construct(private EntityManager $em)
    {
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath);
        }
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
            $name = $this->saveImage($image_path);
            $image->setImagePath($name);
            $post->addImage($image);
        }

        $this->em->persist($post);
        $this->em->flush();

        return true;
    }

    /**
     * Get Image from the storage folder
     */
    public function getImage(string $imageName)
    {
        $filePath = $this->storagePath . $imageName;

        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Store the image to the storage folder
     */
    public function saveImage(string $image_tmp): string
    {
        if (is_file($image_tmp) && exif_imagetype($image_tmp) !== false) {
            // security through obscurity
            $name = md5(strlen($this->storagePath) * mt_rand(0, 99999) . time());
            $ext = image_type_to_extension(exif_imagetype($image_tmp));
            $imageName = $name . $ext;
            $image_path = $this->storagePath . $imageName;

            if (move_uploaded_file($image_tmp, $image_path)) {
                return $imageName;
            }

            throw new Exception("Can't move the uploaded file");
        } else {
            throw new Exception("Not a valid image path!");
        }
    }

    /**
     * Delete the image from the storage folder
     */
    public function deleteImage(Post $post)
    {
        try {
            foreach ($post->getImages() as $image) {
                $image_path = $this->storagePath . $image->getImagePath();
                if (file_exists($image_path)) {
                    if (unlink($image_path)) {
                        continue;
                    } else {
                        throw new Exception('Cannot remove image: ' . $image_path);
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Return post object by its id
     */
    public function getPostById(int $id): Post
    {
        return $this->em->getRepository(Post::class)->find($id);
    }

    /**
     * Return all recent posts
     */
    public function fetchAllPosts()
    {
        return $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function deletePost(Post $post)
    {
        try {
            $this->deleteImage($post);
            $this->em->remove($post);
            $this->em->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

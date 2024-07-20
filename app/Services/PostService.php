<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\ORM\EntityManager;


class PostService
{
    /**
     * Storage Path for the post images
     */
    private string $storagePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR;

    public function __construct(private EntityManager $em)
    {
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
    }
    
    /**
     * Create a new post
     */
    public function createPost(array $data): bool
    {
        $post = new Post();
        $post->setUser($data['user']);
        $post->setCaption($data['caption']);
        $post->setCreatedAt(new \DateTime());
        $post->setIsArchived(false);

        foreach ($data['images'] as $imagePath) {
            $image = new Image();
            $name = $this->saveImage($imagePath);
            $image->setImagePath($name);
            $post->addImage($image);
        }

        $this->em->persist($post);
        $this->em->flush();

        return true;
    }

    /**
     * Delete the post
     */
    public function deletePost(Post $post): bool
    {
        try {
            $this->deleteImages($post);
            $this->em->remove($post);
            $this->em->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
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
    public function saveImage(string $imageTmp): string
    {
        if (is_file($imageTmp) && exif_imagetype($imageTmp) !== false) {
            $name = md5(uniqid('', true));
            $ext = image_type_to_extension(exif_imagetype($imageTmp));
            $imageName = $name . $ext;
            $imagePath = $this->storagePath . $imageName;

            if (move_uploaded_file($imageTmp, $imagePath)) {
                return $imageName;
            }

            throw new Exception("Can't move the uploaded file");
        }

        throw new Exception("Not a valid image path!");
    }

    /**
     * Delete the image from the storage folder
     */
    public function deleteImages(Post $post): bool
    {
        foreach ($post->getImages() as $image) {
            $imagePath = $this->storagePath . $image->getImagePath();
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
        }
        return true;
    }

    /**
     * Return post object by its id
     */
    public function getPostById(int $id): ?Post
    {
        return $this->em->getRepository(Post::class)->find($id);
    }

    /**
     * Return all recent posts
     */
    public function fetchAllPosts(): array
    {
        return $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function toggleLikes(int $postId, User $likedUser): bool
    {
        $post = $this->getPostById($postId);
        if (!$post) {
            throw new Exception("Post not found.");
        }

        $like = $this->em->getRepository(Like::class)->findOneBy([
            'post' => $post,
            'likedUser' => $likedUser
        ]);

        if ($like) {
            $post->removeLike($like);
            $this->em->remove($like);
        } else {
            $like = new Like();
            $like->setPost($post);
            $like->setLikedUser($likedUser);
            $like->setPostOwner($post->getUser());
            $post->addLike($like);
            $this->em->persist($like);
        }

        $this->em->flush();
        return true;
    }

    public function fetchLikedUsers(int $postId): array
    {
        $post = $this->getPostById($postId);
        return $post ? $post->getLikedUsers()->toArray() : [];
    }
}

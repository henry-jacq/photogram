<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Comment;
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

    /**
     * Like or Unlike the post
     */
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
            $this->em->remove($like);
        } else {
            $like = new Like();
            $like->setPost($post);
            $like->setLikedUser($likedUser);
            $like->setPostOwner($post->getUser());
            $this->em->persist($like);
        }

        $this->em->flush();
        return true;
    }

    /**
     * Get all the users who liked the post
     */
    public function fetchLikedUsers(int $postId): array
    {
        $post = $this->getPostById($postId);
        return $post ? $post->getLikedUsers() : [];
    }

    /**
     * Get total likes of the user's posts
     */
    public function getTotalUserLikes(User $user): int
    {
        $qb = $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->select('COUNT(l.id) as totalLikes')
            ->join('p.likes', 'l')
            ->where('p.user = :userId')
            ->setParameter('userId', $user->getId());

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Add a comment to the post
     */
    public function addComment(int $postId, string $content, User $user): int
    {
        $post = $this->getPostById($postId);
        if (!$post) {
            throw new Exception("Post not found.");
        }

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setPostOwner($post->getUser());
        $comment->setCommentUser($user);
        $comment->setContent($content);
        $comment->setCommentDate(new \DateTime());

        $this->em->persist($comment);
        $this->em->flush();

        return $comment->getId() ?? false;
    }

    /**
     * Get all the comments of the post
     */
    public function fetchComments(int $postId): array
    {
        $post = $this->getPostById($postId);

        if (!$post) {
            throw new Exception("Post not found.");
        }

        return $this->em->getRepository(Comment::class)->createQueryBuilder('c')
            ->where('c.post = :post')
            ->setParameter('post', $post)
            ->orderBy('c.commentDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Delete the comment from the post
     */
    public function deleteComment(int $postId, int $commentId): bool
    {
        $post = $this->getPostById($postId);
        if (!$post) {
            throw new Exception("Post not found.");
        }

        $comment = $this->em->getRepository(Comment::class)->find($commentId);
        if (!$comment) {
            throw new Exception("Comment not found.");
        }

        $this->em->remove($comment);
        $this->em->flush();

        return true;
    }
}

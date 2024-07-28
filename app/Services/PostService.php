<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Comment;
use App\Entity\Storage;
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
    public function createPost(array $data): string|bool
    {
        $post = new Post();

        $fullPaths = [];
        $totalImageSize = 0;

        foreach ($data['images'] as $imagePath) {
            $image = new Image();
            $name = $this->saveImage($imagePath);
            $image->setImagePath($name);
            $post->addImage($image);
            $fullPaths[] = $this->storagePath . $name;
            $totalImageSize += filesize($this->storagePath . $name);
        }

        // Update the user's storage
        $result = $this->updateUserStorage($data['user'], $totalImageSize);

        // If storage limit exceeded
        if (!$result) {
            // Delete the images stored
            foreach ($post->getImages() as $image) {
                $imagePath = $this->storagePath . $image->getImagePath();
                if (file_exists($imagePath) && is_file($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Remove the post trying to be created
            $this->em->remove($post);
            $this->em->flush();

            return 'Storage limit exceeded';
        }

        if ($data['ai_caption'] === 'true') {
            $caption = $this->generateCaption($fullPaths);
            $post->setCaption($caption);
        } else {
            $post->setCaption($data['user_caption']);
        }

        $post->setUser($data['user']);
        $post->setCreatedAt(new \DateTime());
        $post->setIsArchived(false);

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
     * Update the user's storage details
     */
    private function updateUserStorage(User $user, int $imageSize, bool $addUsedSpace = true): bool
    {
        $storage = $user->getStorage();

        if (!$storage) {
            throw new Exception("Storage not found.");
        }

        $usedSpace = $storage->getUsedSpace();
        $totalSpace = $storage->getTotalSpace();
        $remainingSpace = $storage->getRemainingSpace();

        if ($addUsedSpace) {
            $usedSpace += $imageSize;
        } else {
            $usedSpace -= $imageSize;
        }

        if ($usedSpace >= $totalSpace) {
            // TODO: Handle storage limit exceeded
            // For example, you can log an error message or send a notification to the user
            // You can also implement a storage cleanup mechanism to free up space
            // Here's an example of logging an error message:
            return false;
            error_log("Storage limit exceeded for user: " . $user->getId());
            throw new Exception("Storage limit exceeded.");
        }

        $remainingSpace = $totalSpace - $usedSpace;

        $storage->setUsedSpace($usedSpace);
        $storage->setRemainingSpace($remainingSpace);
        $this->em->persist($storage);
        $this->em->flush();
        return true;
    }


    /**
     * Generate AI based caption for the images
     */
    public function generateCaption(array $imagePaths): string
    {
        $captions = pyToolExecuter('generate_caption', $imagePaths);
        if (empty($captions)) {
            throw new Exception("Caption generation failed.");
        }

        $output = explode("\n", $captions);

        if (count($output) > 1) {
            $output = $output[array_rand($output)];
        } else {
            $output = $output[0];
        }

        return ucfirst(trim($output));
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
        $user = $post->getUser();
        $totalImageSize = 0;

        foreach ($post->getImages() as $image) {
            $imagePath = $this->storagePath . $image->getImagePath();
            if (file_exists($imagePath) && is_file($imagePath)) {
                $totalImageSize += filesize($imagePath);
                unlink($imagePath);
            }
        }

        // Update the user's storage
        $this->updateUserStorage($user, $totalImageSize, false);
        
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
            ->andWhere('p.isArchived = 0')
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

    /**
     * Update the post caption
     */
    public function updatePostCaption(Post $post, string $caption): bool
    {
        $post->setCaption($caption);
        $this->em->persist($post);
        $this->em->flush();
        return true;
    }

    /**
     * Archive or Unarchive the post
     */
    public function togglePostArchive(int $postId, User $user): bool
    {
        $post = $this->getPostById($postId);
        if (!$post) {
            throw new Exception("Post not found.");
        }

        // To prevent unauthorized access
        if ($post->getUser()->getId() !== $user->getId()) {
            throw new Exception("Unauthorized.");
        }

        $post->setIsArchived(!$post->getIsArchived());
        $this->em->persist($post);
        $this->em->flush();
        return true;
    }

    /**
     * Return all recent posts
     */
    public function fetchAllPosts($archived = false): array
    {
        $queryBuilder = $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC');

        if (!$archived) {
            $queryBuilder->where('p.isArchived = 0');
        }

        return $queryBuilder->getQuery()->getResult();

    }

    /**
     * Fetch all the posts of the user
     */
    public function fetchUserPosts(User $user, bool $archived = false): array
    {
        $queryBuilder = $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.user = :user')
            ->orderBy('p.createdAt', 'DESC');

        if (!$archived) {
            $queryBuilder->andWhere('p.isArchived = 0');
        }

        $queryBuilder->setParameter('user', $user);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Fetch all the archived posts of the user
     */
    public function fetchArchivedPosts(User $user): array
    {
        return $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.user = :user')
            ->andWhere('p.isArchived = 1')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fetch all the posts liked by the user
     */
    public function fetchUserLikedPosts(User $user): array
    {
        return $this->em->getRepository(Post::class)->createQueryBuilder('p')
            ->join('p.likes', 'l')
            ->where('l.likedUser = :user')
            ->andWhere('p.isArchived = 0')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}

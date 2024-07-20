<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\UserData;
use Doctrine\ORM\EntityManager;


class UserService
{
    /**
     * Storage Path for the user avatars
     */
    private string $storagePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR;

    public function __construct(private EntityManager $em)
    {
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
    }

    /**
     * Create a new user
     */
    public function createUser(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Delete the user
     */
    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Update the user data
     */
    public function updateUserData(User $user, array $data): UserData
    {
        $userData = $user->getUserData();
        $originalData = clone $userData; // Create a copy of the original user data

        $fields = ['fullname', 'website', 'jobTitle', 'bio', 'location', 'linkedin', 'instagram'];

        foreach ($fields as $field) {
            $userData->{"set" . ucfirst($field)}($data[$field]);
        }
        
        // If uploaded avatar is valid, set it
        if ($data['avatar']->getError() === UPLOAD_ERR_OK) {
            if ($userData->getProfileAvatar() !== 'default.png') {
                $this->deleteAvatar($userData);
            }
            $name = $this->saveAvatar($data['avatar']->getFilePath());
            $userData->setProfileAvatar($name);
        }

        // Check if the user data has been modified
        if ($userData != $originalData) {
            $this->em->persist($userData);
            $this->em->flush();
        }

        return $userData;
    }

    /**
     * Get the user by id
     */
    public function getUserById(int $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);
        return $user;
    }

    /**
     * Get the user by email or username
     */
    public function getUserByEmailOrUsername(string $emailOrUsername): ?User
    {
        $user = $this->em->getRepository(User::class)->createQueryBuilder('u')
        ->where('u.email = :emailOrUsername')
        ->orWhere('u.username = :emailOrUsername')
        ->setParameter('emailOrUsername',
            $emailOrUsername
        )
        ->getQuery()
        ->getOneOrNullResult();

        return $user;
    }

    /**
     * Return the avatar image
     */
    public function getAvatar(string $imageName)
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $imageName;

        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Store the image to the storage folder
     */
    public function saveAvatar(string $image_tmp): string
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
            throw new Exception("Not a valid avatar path!");
        }
    }

    /**
     * Delete the user avatar from the storage folder
     */
    public function deleteAvatar(UserData $ud)
    {
        try {
            $image_path = $this->storagePath . $ud->getProfileAvatar();
            if (file_exists($image_path)) {
                try {
                    unlink($image_path);
                } catch (Exception $e) {
                    throw new Exception('Cannot remove avatar: ' . $image_path);
                }
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function fetchAllUsers(): array
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $users;
    }

    public function fetchUserPosts(User $user): array
    {
        $posts = $this->em->getRepository(Post::class)->findBy(['user' => $user], ['createdAt' => 'DESC']);
        return $posts;
    }
}

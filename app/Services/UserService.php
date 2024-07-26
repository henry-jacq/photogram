<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Entity\User;
use App\Entity\Follow;
use App\Entity\UserData;
use App\Entity\UserEmail;
use InvalidArgumentException;
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
    public function getUserById(int $id): ?User
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

    /**
     * Add a new email to the user
     */
    public function addEmail(User $user, string $email): bool
    {
        $userEmail = new UserEmail();
        $userEmail->setUser($user);
        $userEmail->setEmail($email);

        $this->em->persist($userEmail);
        $this->em->flush();

        return true;
    }

    /**
     * Remove the email from the user
     */
    public function removeEmail(User $user, string $email): bool
    {
        $userEmail = $this->em->getRepository(UserEmail::class)->findOneBy(['user' => $user, 'email' => $email]);
        if ($userEmail) {
            $this->em->remove($userEmail);
            $this->em->flush();
            return true;
        }
        return false;
    }

    /**
     * Set the primary email for the user
     */
    public function setPrimaryEmail(User $user, string $email): bool
    {
        $userEmails = $this->em->getRepository(UserEmail::class)->findBy(['user' => $user]);
        foreach ($userEmails as $userEmail) {
            $userEmail->setPrimary($userEmail->getEmail() === $email);
            $this->em->persist($userEmail);
        }
        $this->em->flush();
        return true;
    }

    /**
     * Update the user theme
     */
    public function updateTheme(User $user, string $theme)
    {
        $user = $user->getPreferences()->setTheme($theme);
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }

    public function toggleFollow(User $user, int $followerId): bool
    {
        $followUser = $this->getUserById($followerId);
        $followRepository = $this->em->getRepository(Follow::class);

        // Check if a follow relationship already exists
        $existingFollow = $followRepository->findOneBy([
            'user' => $user,
            'followUser' => $followUser,
        ]);

        if ($existingFollow) {
            // Unfollow logic
            $user->removeFollowing($existingFollow);
            $followUser->removeFollower($existingFollow);

            $this->em->remove($existingFollow);
            $this->em->flush();

            return false; // Return false indicating unfollowed
        } else {
            // Follow logic
            $follow = new Follow();
            $follow->setUser($user);
            $follow->setFollowUser($followUser);
            $this->em->persist($follow);
            $this->em->flush();

            return true; // Return true indicating followed
        }
    }

    public function getFollowers(User $user)
    {
        $followers = [];
        foreach ($user->getFollowers() as $follower) {
            $followUser = $follower->getUser();
            $followers[] = [
                'id' => $followUser->getId(),
                'username' => $followUser->getUsername(),
                'fullname' => $followUser->getUserData()->getFullname(),
                'avatar' => $followUser->getUserData()->getAvatarURL(),
            ];
        }
        return $followers;
    }

    public function getFollowings(User $user)
    {
        $followers = [];
        foreach ($user->getFollowings() as $follower) {
            $followUser = $follower->getFollowUser();
            $followers[] = [
                'id' => $followUser->getId(),
                'username' => $followUser->getUsername(),
                'fullname' => $followUser->getUserData()->getFullname(),
                'avatar' => $followUser->getUserData()->getProfileAvatar(),
            ];
        }
        return $followers;
    }
}

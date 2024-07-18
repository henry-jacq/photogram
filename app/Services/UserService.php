<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManager;


class UserService
{
    public function __construct(private EntityManager $em)
    {
    }

    public function getUserById(int $id): User
    {
        $user = $this->em->getRepository(User::class)->find($id);
        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        return $user;
    }

    public function createUser(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function fetchAllUsers(): array
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $users;
    }

    public function fetchUserPosts(User $user): array
    {
        $posts = $user->getPosts();
        return iterator_to_array($posts);
    }

    public function getAvatar(string $imageName)
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $imageName;

        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }
}

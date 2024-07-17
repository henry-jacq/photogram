<?php

namespace App\Core;

use Exception;
use App\Entity\User;
use App\Core\Session;
use App\Entity\UserData;
use Doctrine\ORM\EntityManager;

class Auth
{ 
    public function __construct(
        private readonly EntityManager $manager,
        private readonly Session $session,
    )
    {
    }

    /**
     * Register user
     */
    public function register(array $credentials)
    {
        // Check for existing user with the same username or email
        $user = $this->manager->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $credentials['username'])
            ->setParameter('email', $credentials['email'])
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            $user = new User();
            $userData = new UserData();
            $user->setEmail($credentials['email']);
            $user->setUsername($credentials['username']);
            $user->setPassword(password_hash($credentials['password'], PASSWORD_DEFAULT, ['cost' => 12]));
            $user->setCreatedAt(new \DateTime());
            $userData->setUser($user);
            $userData->setFullName($credentials['fullname']);
            $this->manager->persist($user);
            $this->manager->persist($userData);
            $this->manager->flush();
            return true;
        }

        return false;
    }

    /**
     * Login user with username or email
     */
    public function login($user, $password)
    {
        // Check if the user exists with the given username or email
        $user = $this->manager->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.username = :user OR u.email = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();

        if ($user && password_verify($password, $user->getPassword())) {
            $this->session->put('user', $user->getId());
            return true;
        }

        return false;
    }
    
    /**
     * Logout user from the session
     */
    public function logout(): void
    {
        $this->session->forget('user');
    }

    /**
     * Check if the user is logged in or not
     */
    public function isAuthenticated(): bool
    {
        if ($this->session->get('user') !== null) {
            return true;
        }
        return false;
    }
}
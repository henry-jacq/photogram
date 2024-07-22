<?php

namespace App\Services;

use App\Entity\User;
use App\Core\Session;
use App\Entity\Storage;
use App\Entity\UserData;
use App\Entity\Preferences;
use App\Entity\Subscription;
use App\Enum\PreferredTheme;
use App\Enum\StorageSpace;
use App\Enum\SubscriptionPlan;
use Doctrine\ORM\EntityManager;

class AuthService
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
            $user->setEmail($credentials['email']);
            $user->setUsername($credentials['username']);
            $user->setPassword(password_hash($credentials['password'], PASSWORD_DEFAULT, ['cost' => 12]));
            $user->setCreatedAt(new \DateTime());
            $this->manager->persist($user);
            
            $userData = new UserData();
            $userData->setUser($user);
            $userData->setFullName($credentials['fullname']);
            $userData->setProfileAvatar('default.png');
            $this->manager->persist($userData);

            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setPlan(SubscriptionPlan::Free->value);
            $subscription->setStartDate(new \DateTime());
            $subscription->setIsActive(true);
            $this->manager->persist($subscription);

            $storage = new Storage();
            $storage->setUser($user);
            $storage->setUsedSpace(0);
            $free_space = StorageSpace::Free->value; // In Bytes
            $storage->setTotalSpace($free_space);
            $storage->setRemainingSpace($free_space);
            $this->manager->persist($storage);

            $preferences = new Preferences();
            $preferences->setUser($user);
            $preferences->setTheme(PreferredTheme::Dark->value);
            $this->manager->persist($preferences);
            
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

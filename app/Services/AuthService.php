<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Session;
use App\Entity\Storage;
use App\Entity\UserData;
use App\Enum\StorageSpace;
use App\Entity\Preferences;
use App\Entity\UserSession;
use App\Entity\Subscription;
use App\Enum\PreferredTheme;
use App\Enum\SubscriptionPlan;
use Doctrine\ORM\EntityManager;

class AuthService
{ 
    public function __construct(
        private readonly Session $session,
        private readonly UserService $user,
        private readonly EntityManager $em,
    )
    {
    }

    /**
     * Register user
     */
    public function register(array $credentials)
    {
        // Check for existing user with the same username or email
        $user = $this->em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $credentials['username'])
            ->setParameter('email', $credentials['email'])
            ->getQuery()
            ->getOneOrNullResult();

        // If the user exists, return false
        if ($user) {
            return false;
        }

        $user = new User();
        $user->setEmail($credentials['email']);
        $user->setUsername($credentials['username']);
        $user->setPassword(password_hash($credentials['password'], PASSWORD_DEFAULT, ['cost' => 12]));
        $user->setCreatedAt(new \DateTime());
        $this->em->persist($user);
        
        $userData = new UserData();
        $userData->setUser($user);
        $userData->setFullName($credentials['fullname']);
        $userData->setProfileAvatar('default.png');
        $this->em->persist($userData);

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setPlan(SubscriptionPlan::Free->value);
        $subscription->setStartDate(new \DateTime());
        $subscription->setIsActive(true);
        $this->em->persist($subscription);

        $storage = new Storage();
        $storage->setUser($user);
        $storage->setUsedSpace(0);
        $free_space = StorageSpace::Free->value; // In Bytes
        $storage->setTotalSpace($free_space);
        $storage->setRemainingSpace($free_space);
        $this->em->persist($storage);

        $preferences = new Preferences();
        $preferences->setUser($user);
        $preferences->setTheme(PreferredTheme::Dark->value);
        $this->em->persist($preferences);
        
        $this->em->flush();
        return true;
    }

    /**
     * Login user with username or email
     */
    public function login(array $data): bool
    {
        // Check if the user exists with the given username or email
        $user = $this->em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.username = :user OR u.email = :user')
            ->setParameter('user', $data['user'])
            ->getQuery()
            ->getOneOrNullResult();

        if ($user && password_verify($data['password'], $user->getPassword())) {
            $sessionKey = $this->createSession(
                $user, $data['ipAddress'], $data['userAgent']
            );
            $this->session->regenerate();
            $this->session->put('user', $user->getId());
            $this->session->put('sessionKey', $sessionKey);
            return true;
        }

        return false;
    }
    
    /**
     * Logout user from the session
     */
    public function logout(): void
    {
        $sessionKey = $this->session->get('sessionKey');
        if ($sessionKey) {
            $this->terminateSession($sessionKey);
            $this->session->forget('sessionKey');
        }
        $this->session->forget('user');
        $this->session->forget('userData');
        $this->session->forget('sessionKey');
        $this->session->regenerate();
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

    /**
     * Create a new session for the user
     */
    public function createSession(User $user, string $ipAddress, string $userAgent): string
    {
        $session = new UserSession();
        $session->setUser($user);
        $session->setLoginTime(new DateTime());
        $session->setIpAddress($ipAddress);
        $session->setUserAgent($userAgent);

        // Generate a session hash key
        $hashKey = $this->generateSessionHashKey($session->getLoginTime(), $userAgent, $ipAddress);
        $session->setHashKey($hashKey);

        $this->em->persist($session);
        $this->em->flush();

        return $hashKey;
    }

    /**
     * Get the active sessions for the user
     */
    public function getActiveSessions(User $user): array
    {
        return $this->em->getRepository(UserSession::class)->findBy(['user' => $user]);
    }

    public function getUserSession(string $hashKey): UserSession
    {
        return $this->em->getRepository(UserSession::class)->findOneBy(['hashKey' => $hashKey]);
    }

    /**
     * Terminate the session by hash key
     */
    public function terminateSession(string $hashKey): bool
    {
        $session = $this->getUserSession($hashKey);
        if ($session) {
            $this->em->remove($session);
            $this->em->flush();
            return true;
        }
    }

    /**
     * Terminate the session by ID
     */
    public function terminateSessionById(int $id): bool
    {
        $session = $this->em->getRepository(UserSession::class)->find($id);
        if ($session) {
            $this->em->remove($session);
            $this->em->flush();
            return true;
        }
        return false;
    }

    /**
     * Terminate all the sessions for the user
     */
    public function terminateUserSessions(int $userId): bool
    {
        $sessions = $this->em->getRepository(UserSession::class)->findBy(['user' => $userId]);

        if ($sessions) {
            foreach ($sessions as $session) {
                $this->em->remove($session);
            }
            $this->em->flush();
            return true;
        }
        return false;
    }

    /**
     * Terminate all the sessions for the user
     */
    private function generateSessionHashKey(\DateTime $loginTime, string $userAgent, string $ipAddress): string
    {
        return md5($loginTime->getTimestamp() . $userAgent . $ipAddress);
    }

    /**
     * Validate the session hash key
     */
    public function validateSessionHashKey(User $user, string $userAgent, string $ipAddress): bool
    {
        $activeSessions = $this->getActiveSessions($user);
        foreach ($activeSessions as $session) {
            $hashKey = $this->generateSessionHashKey($session->getLoginTime(), $userAgent, $ipAddress);
            if ($session->getHashKey() === $hashKey) {
                return true;
            }
        }
        return false;
    }
}

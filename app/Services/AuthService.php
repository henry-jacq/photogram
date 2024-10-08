<?php

namespace App\Services;

use DateTime;
use Exception;
use App\Entity\User;
use App\Core\Session;
use App\Entity\Storage;
use App\Entity\UserData;
use App\Entity\UserEmail;
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
        private readonly MailService $mailer,
        private readonly EntityManager $em,
    )
    {
    }

    /**
     * Register user
     */
    public function register(array $credentials)
    {
        $existingUser = $this->user->exists([
            'username' => $credentials['username'],
            'email' => $credentials['email']
        ]);

        // If the user exists, return false
        if ($existingUser) {
            return false;
        }

        $user = new User();
        $user->setUsername($credentials['username']);
        $user->setPassword(password_hash($credentials['password'], PASSWORD_DEFAULT, ['cost' => 12]));
        $user->setCreatedAt(new \DateTime());
        $user->setActive(0);
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

        $userEmail = new UserEmail();
        $userEmail->setUser($user);
        $userEmail->setEmail($credentials['email']);
        $userEmail->setIsPrimary(true);
        $this->em->persist($userEmail);
        
        $this->em->flush();
        return true;
    }

    /**
     * Login user with username or email
     */
    public function login(array $data)
    {
        // Check if the session is already active
        $currentSessionID = $this->session->getId();
        if ($this->getUserSessionById($currentSessionID) !== null) {
            // Cannot login if the session is already active
            return false;
        }

        $user = $this->user->getByUsername($data['username']);

        if ($user && password_verify($data['password'], $user->getPassword())) {
            
            $this->session->regenerate();

            $session = $this->createSession(
                $user, $data['ipAddress'], $data['userAgent']
            );

            $this->session->put('user', $user->getId());
            $this->session->put('userSession', $session);
            return true;
        }

        return false;
    }

    public function isUserActive(string $username)
    {
        $user = $this->user->getByUsername($username);
        if ($user && $user->getActive()) {
            return true;
        }
        return $user;
    }

    public function sendActivationEmail(User $user)
    {
        $html = $this->mailer->getMailTemplate("activation", [
            'userName' => $user->getUsername(),
            'passCode' => $user->getPassCode()
        ]);
        $this->mailer->addRecipient($user->getPrimaryEmail());
        $this->mailer->addSubject('[Photogram] Your Account Activation Code');
        $this->mailer->isHTML(true);
        $this->mailer->addBody($html);
        return $this->mailer->sendMail();
    }

    public function updatePassCode(User $user)
    {
        // TODO: Add expiry time for the passcode by modifying the User entity
        $user->setPassCode(mt_rand(100000, 999999));
        $this->em->persist($user);
        $this->em->flush();
    }

    public function resendActivationEmail(string $username)
    {
        $user = $this->user->getByUsername($username);
        if ($user) {
            $this->updatePassCode($user);
            $this->sendActivationEmail($user);
            return true;
        }
        return false;
    }

    /**
     * Activate the user account
     */
    public function activateAccount(User $user, int $passcode)
    {
        if ($user && $user->getPassCode() === $passcode) {
            $user->setActive(1);
            $this->em->persist($user);
            $this->em->flush();
            return true;
        }
        return false;
    }
    
    /**
     * Logout user from the session
     */
    public function logout(): void
    {
        $session = $this->session->get('userSession');
        if ($session instanceof UserSession) {
            $this->terminateSession($session);
            $this->session->forget('userSession');
        }
        $this->session->forget('user');
        $this->session->forget('userData');
        $this->session->deleteCookie('session_token');
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
    public function createSession(User $user, string $ipAddress, string $userAgent): UserSession
    {
        $session = new UserSession();
        $id = $this->session->getId();
        $session->setUser($user);
        $session->setSessionId($id);
        $session->setIpAddress($ipAddress);
        $session->setUserAgent($userAgent);

        $time = new DateTime();
        $session->setLoginTime($time);
        $session->setLastActivity($time);

        $token = bin2hex(random_bytes(32));
        $session->setSessionToken($token);
        $this->session->createCookie('session_token', $token, 0);


        $this->em->persist($session);
        $this->em->flush();

        return $session;
    }

    /**
     * Regenerate the session token
     */
    public function regenerateSessionToken(UserSession $session): string
    {
        $newToken = bin2hex(random_bytes(32));
        $this->session->createCookie('session_token', $newToken, 0);
        $session->setSessionToken($newToken);
        $this->em->persist($session);
        $this->em->flush();
        return $newToken;
    }

    /**
     * Get the session token
     */
    public function getSessionToken()
    {
        return $this->session->getCookie('session_token');
    }

    /**
     * Update the last activity of the session
     */
    public function updateLastActivity(UserSession $session): void
    {
        $session->setLastActivity(new DateTime());
        $this->em->persist($session);
        $this->em->flush();
    }

    /**
     * Get the active sessions for the user
     */
    public function fetchSessions(User $user): array
    {
        return $this->em->getRepository(UserSession::class)->findBy(['user' => $user]);
    }

    /**
     * Get the user session by metadata
     */
    public function getUserSession(array $metaData): ?UserSession
    {
        return $this->em->getRepository(UserSession::class)->findOneBy([
            'sessionId' => $metaData['sessionId'],
            'sessionToken' => $metaData['sessionToken'],
            'userAgent' => $metaData['userAgent'],
            'ipAddress' => $metaData['ipAddress'],
        ]);
    }

    /**
     * Get the user session by ID
     */
    public function getUserSessionById(string $id): ?UserSession
    {
        return $this->em->getRepository(UserSession::class)->findOneBy([
            'sessionId' => $id,
        ]);
    }

    /**
     * Terminate the session
     */
    public function terminateSession(UserSession $userSession): bool
    {
        // Check if the entity is managed
        if (!$this->em->contains($userSession)) {
            // Fetch the entity from the database
            $userSession = $this->getUserSessionById($userSession->getSessionId());
        }
        
        if (is_null($userSession)) {
            return false;
        }
        
        // Remove the entity
        $this->em->remove($userSession);
        $this->em->flush();
        return true;
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
        $sessions = $this->em->getRepository(UserSession::class)->findBy(['user_id' => $userId]);

        if ($sessions) {
            foreach ($sessions as $session) {
                $this->em->remove($session);
            }
            $this->em->flush();
            return true;
        }
        return false;
    }
}

<?php

namespace App\Entity;

use App\Entity\Follow;
use App\Entity\UserEmail;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity, ORM\Table(name: 'users')]
class User
{
    #[ORM\Id, ORM\Column(type: 'integer', options: ['unsigned' => true]), ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'string', name: 'reset_token', nullable: true)]
    private ?string $resetToken;

    #[ORM\Column(type: 'datetime', name: 'reset_token_expiry', nullable: true)]
    private ?\DateTime $resetTokenExpiry;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private UserData $userData;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserEmail::class, cascade: ['persist', 'remove'])]
    private Collection $emails;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Preferences $preferences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payment::class)]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Follow::class)]
    private Collection $following;

    #[ORM\OneToMany(mappedBy: 'followUser', targetEntity: Follow::class)]
    private Collection $followers;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenExpiry(): ?\DateTime
    {
        return $this->resetTokenExpiry;
    }

    public function setResetTokenExpiry(?\DateTime $resetTokenExpiry): self
    {
        $this->resetTokenExpiry = $resetTokenExpiry;
        return $this;
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public function setUserData(UserData $userData): self
    {
        $this->userData = $userData;
        return $this;
    }

    public function getPreferences(): Preferences
    {
        return $this->preferences;
    }

    public function setPreferences(Preferences $preferences): self
    {
        $this->preferences = $preferences;
        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setUser($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            if ($subscription->getUser() === $this) {
                $subscription->setUser(null);
            }
        }

        return $this;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setUser($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            if ($payment->getUser() === $this) {
                $payment->setUser(null);
            }
        }

        return $this;
    }

    public function addFollower(Follow $follow): self
    {
        if (!$this->followers->contains($follow)) {
            $this->followers[] = $follow;
            $follow->setFollowUser($this);
        }

        return $this;
    }

    public function removeFollower(Follow $follow): self
    {
        if ($this->followers->removeElement($follow)) {
            // Set the owning side to null (unless already changed)
            if ($follow->getFollowUser() === $this) {
                $follow->setFollowUser(null);
            }
        }

        return $this;
    }

    public function addFollowing(Follow $follow): self
    {
        if (!$this->following->contains($follow)) {
            $this->following[] = $follow;
            $follow->setUser($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $follow): self
    {
        if ($this->following->removeElement($follow)) {
            // Set the owning side to null (unless already changed)
            if ($follow->getUser() === $this) {
                $follow->setUser(null);
            }
        }

        return $this;
    }

    public function isFollowing(User $user): bool
    {
        foreach ($this->following as $follow) {
            if ($follow->getFollowUser() === $user) {
                return true;
            }
        }

        return false;
    }

    // Getters for the collections
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function getFollowings(): Collection
    {
        return $this->following;
    }

    public function getFollowersCount(): int
    {
        return $this->followers->count();
    }

    public function getFollowingsCount(): int
    {
        return $this->following->count();
    }

    /**
     * @return Collection|UserEmail[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(UserEmail $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->setUser($this);
        }

        return $this;
    }

    public function removeEmail(UserEmail $email): self
    {
        if ($this->emails->removeElement($email)) {
            // set the owning side to null (unless already changed)
            if ($email->getUser() === $this) {
                $email->setUser(null);
            }
        }

        return $this;
    }

    public function getPrimaryEmail(): ?UserEmail
    {
        foreach ($this->emails as $email) {
            if ($email->isPrimary()) {
                return $email;
            }
        }
        return null;
    }
}

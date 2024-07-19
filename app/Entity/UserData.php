<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'user_data')]
class UserData
{
    #[ORM\Id, ORM\OneToOne(targetEntity: User::class, inversedBy: 'userData'), ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'full_name', type: 'string', nullable: true)]
    private ?string $fullName;

    #[ORM\Column(name: 'profile_avatar', type: 'string', nullable: true)]
    private ?string $profileAvatar;

    #[ORM\Column(name: 'website', type: 'string', nullable: true)]
    private ?string $website;

    #[ORM\Column(name: 'job_title', type: 'string', nullable: true)]
    private ?string $jobTitle;

    #[ORM\Column(name: 'bio', type: 'text', nullable: true)]
    private ?string $bio;

    #[ORM\Column(name: 'location', type: 'string', nullable: true)]
    private ?string $location;

    #[ORM\Column(name: 'instagram_handle', type: 'string', nullable: true)]
    private ?string $instagramHandle;

    #[ORM\Column(name: 'linkedin_handle', type: 'string', nullable: true)]
    private ?string $linkedinHandle;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullName;
    }

    public function setFullname(?string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Return the profile avatar URL
     */
    public function getAvatarURL()
    {
        if ($this->profileAvatar == 'default.png') {
            return '/assets/random/default.png';
        }
        return '/files/avatars/' . $this->profileAvatar;
    }

    /**
     * Return the profile avatar name
     */
    public function getProfileAvatar()
    {
        return $this->profileAvatar;
    }

    public function setProfileAvatar(?string $profileAvatar): self
    {
        $this->profileAvatar = $profileAvatar;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagramHandle;
    }

    public function setInstagram(?string $instagramHandle): self
    {
        $this->instagramHandle = $instagramHandle;
        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedinHandle;
    }

    public function setLinkedin(?string $linkedinHandle): self
    {
        $this->linkedinHandle = $linkedinHandle;
        return $this;
    }
}

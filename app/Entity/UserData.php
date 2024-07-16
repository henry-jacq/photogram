<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;


#[Entity, Table('user_data')]
class UserData
{
    #[Id, Column(options: ['foreign' => true])]
    private int $user_id;

    #[Column]
    private string $fullname;

    #[Column]
    private string $avatar;

    #[Column]
    private string $website;

    #[Column]
    private string $job_title;

    #[Column]
    private string $bio;

    #[Column]
    private string $location;

    #[Column]
    private string $instagram_handle;

    #[Column]
    private string $linkedin_handle;
}

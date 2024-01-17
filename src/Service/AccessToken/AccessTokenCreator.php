<?php

namespace App\Service\AccessToken;

use App\Entity\AccessToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class AccessTokenCreator
{
    public function __construct(private EntityManagerInterface $em) {}

    public function create(User $user): AccessToken
    {
        $token = bin2hex(random_bytes(64));

        $accessToken = new AccessToken($user, $token);

        $this->em->persist($accessToken);
        $this->em->flush();

        return $accessToken;
    }
}
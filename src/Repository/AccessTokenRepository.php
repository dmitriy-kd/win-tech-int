<?php

namespace App\Repository;

use App\Entity\AccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly UserRepository $userRepository,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, AccessToken::class);
    }

    public function getTokenByUsername(string $username): AccessToken
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        return $this->findOneBy(['user' => $user]);
    }
}
<?php

namespace App\Service\User;

use App\Form\Model\LoginUserModel;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class UserAuthenticator
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function isUserAuthenticated(LoginUserModel $userModel): bool
    {
        $user = $this->userRepository->findOneBy(['username' => $userModel->username]);

        return $user && $this->passwordHasher->isPasswordValid($user, $userModel->password);
    }
}
<?php

namespace App\Security;

use App\Entity\AccessToken;
use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private AccessTokenRepository $accessTokenRepository) {}

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        /** @var AccessToken $accessToken */
        $accessToken = $this->accessTokenRepository->find($accessToken);

        if (!$accessToken) {
            throw new BadCredentialsException('Invalid credentials');
        }

        return new UserBadge($accessToken->getUser()->getUserIdentifier());
    }
}
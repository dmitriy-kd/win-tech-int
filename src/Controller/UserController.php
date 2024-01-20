<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\LoginUserModel;
use App\Form\Type\LoginUserType;
use App\Form\Type\RegistrationFormType;
use App\Repository\AccessTokenRepository;
use App\Service\AccessToken\AccessTokenCreator;
use App\Service\Helper\FormErrorsHelper;
use App\Service\User\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
        private readonly AccessTokenCreator $accessTokenCreator,
        private readonly FormErrorsHelper $formErrorsHelper,
        private readonly UserAuthenticator $userAuthenticator,
        private readonly AccessTokenRepository $accessTokenRepository
    ) {}

    #[Route(
        '/api/user/register',
        name: 'api_user_register',
        methods: ['POST']
    )]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            $accessToken = $this->accessTokenCreator->create($user);

            return new JsonResponse([
                'access_token' => $accessToken->getToken()
            ]);
        }

        $errors = $this->formErrorsHelper->prepareApiErrors($form);

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    #[Route(
        '/api/user/get-access-token',
        name: 'api_user_get_access_token',
        methods: ['POST']
    )]
    public function getAccessToken(Request $request): Response
    {
        $form = $this->createForm(LoginUserType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var LoginUserModel $loginUserModel */
            $loginUserModel = $form->getData();

            if ($this->userAuthenticator->isUserAuthenticated($loginUserModel)) {
                $accessToken = $this->accessTokenRepository->getTokenByUsername($loginUserModel->username);

                return new JsonResponse(['access_token' => $accessToken->getToken()]);
            }

            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        $errors = $this->formErrorsHelper->prepareApiErrors($form);

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}
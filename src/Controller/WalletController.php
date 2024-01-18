<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\Type\CreateWalletType;
use App\Service\Helper\FormErrorsHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class WalletController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FormErrorsHelper $formErrorsHelper
    ) {}

    #[Route(
        '/api/wallet/create',
        name: 'api_wallet_create',
        methods: ['POST']
    )]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getWallet()) {
            return new JsonResponse(
                [
                    'errors' => [
                        'common' => ['This user has already have the wallet']
                    ]
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $wallet = new Wallet($user);

        $form = $this->createForm(CreateWalletType::class, $wallet)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($wallet);
            $this->em->flush();

            return new JsonResponse(['wallet_id' => $wallet->getId()]);
        }

        $errors = $this->formErrorsHelper->prepareApiErrors($form);

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    #[Route(
        '/api/wallet/{id}/balance',
        name: 'api_wallet_get_balance',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function getBalance(Wallet $wallet): Response
    {
        return $this->json(
            $wallet,
            context: [AbstractNormalizer::GROUPS => ['api-wallet-get-balance']]
        );
    }
}
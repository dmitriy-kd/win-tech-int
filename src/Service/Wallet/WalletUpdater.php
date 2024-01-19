<?php

namespace App\Service\Wallet;

use App\Entity\ExchangeRate;
use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class WalletUpdater
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExchangeRateRepository $exchangeRateRepository
    ) {}

    public function updateBalance(WalletTransaction $walletTransaction, Wallet $wallet): void
    {
        $newBalance = $walletTransaction->getAmount();

        if ($walletTransaction->getCurrency() !== $wallet->getCurrency()) {
            /** @var ExchangeRate $exchangeRate */
            $exchangeRate = $this->exchangeRateRepository->find([
                'currencyFrom' => $walletTransaction->getCurrency(),
                'currencyTo' => $wallet->getCurrency()
            ]);

            $newBalance = $newBalance * $exchangeRate->getRate() / 100;
        }

        $wallet->setBalance($newBalance);

        $this->em->flush();
    }
}
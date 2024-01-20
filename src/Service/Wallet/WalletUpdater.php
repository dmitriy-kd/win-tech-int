<?php

namespace App\Service\Wallet;

use App\Entity\Wallet;
use App\Entity\WalletTransaction;
use App\Enum\WalletTransactionReasonEnum;
use App\Service\ExchangeRate\AmountConverter;
use Doctrine\ORM\EntityManagerInterface;

final readonly class WalletUpdater
{
    public function __construct(
        private EntityManagerInterface $em,
        private AmountConverter $amountConverter
    ) {}

    public function updateBalance(WalletTransaction $walletTransaction, Wallet $wallet): void
    {
        $amount = $walletTransaction->getAmount();

        if ($walletTransaction->getCurrency() !== $wallet->getCurrency()) {
            $amount = $this->amountConverter->convert(
                $walletTransaction->getCurrency(),
                $wallet->getCurrency(),
                $amount
            );
        }

        $balance = $wallet->getBalance();

        $newBalance = match ($walletTransaction->getReason()) {
            WalletTransactionReasonEnum::STOCK => $balance + $amount,
            WalletTransactionReasonEnum::REFUND => $balance - $amount
        };

        $wallet->setBalance($newBalance);

        $this->em->flush();
    }
}
<?php

namespace App\Validator\Constraint;

use App\Entity\WalletTransaction;
use App\Enum\WalletTransactionReasonEnum;
use App\Service\ExchangeRate\AmountConverter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WalletTransactionAmountConstraintValidator extends ConstraintValidator
{
    public function __construct(private readonly AmountConverter $amountConverter) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        /** @var WalletTransaction $walletTransaction */
        $walletTransaction = $value;

        if ($walletTransaction->getReason() === WalletTransactionReasonEnum::REFUND) {
            $wallet = $walletTransaction->getWallet();

            $amount = $walletTransaction->getAmount();

            if ($walletTransaction->getCurrency() !== $wallet->getCurrency()) {
                $amount = $this->amountConverter->convert(
                    $walletTransaction->getCurrency(),
                    $wallet->getCurrency(),
                    $amount
                );
            }

            if ($wallet->getBalance() < $amount) {
                $this->context->addViolation('Refund transaction amount cannot be greater than wallet balance');
            }
        }
    }
}
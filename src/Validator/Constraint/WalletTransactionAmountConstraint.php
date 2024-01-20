<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class WalletTransactionAmountConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
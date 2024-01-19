<?php

namespace App\Enum;

enum WalletTransactionTypeEnum: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}

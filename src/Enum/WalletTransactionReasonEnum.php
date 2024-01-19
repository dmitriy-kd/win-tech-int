<?php

namespace App\Enum;

enum WalletTransactionReasonEnum: string
{
    case STOCK = 'stock';
    case REFUND = 'refund';
}

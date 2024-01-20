<?php

namespace App\Enum;

enum CurrencyEnum: string
{
    case USD = 'usd';
    case RUB = 'rub';

    public static function getList(): array
    {
        return [
            self::RUB,
            self::USD
        ];
    }
}

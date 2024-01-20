<?php

namespace App\Service\ExchangeRate;

use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;

final readonly class AmountConverter
{
    public function __construct(
        private ExchangeRateRepository $exchangeRateRepository
    ) {}

    /**
     * @param int $amount Amount in cents
     * @return int Amount in cents
     */
    public function convert(CurrencyEnum $currencyFrom, CurrencyEnum $currencyTo, int $amount): int
    {
        $exchangeRate = $this->exchangeRateRepository->find([
            'currencyFrom' => $currencyFrom,
            'currencyTo' => $currencyTo
        ]);

        return $amount * $exchangeRate->getRate() / 100;
    }
}
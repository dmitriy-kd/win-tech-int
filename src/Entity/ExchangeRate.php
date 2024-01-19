<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\Column(length: 3)]
    private CurrencyEnum $currencyFrom;

    #[ORM\Id]
    #[ORM\Column(length: 3)]
    private CurrencyEnum $currencyTo;

    #[ORM\Column]
    private int $rate;

    public function __construct(CurrencyEnum $currencyFrom, CurrencyEnum $currencyTo, int $rate)
    {
        $this->currencyFrom = $currencyFrom;
        $this->currencyTo = $currencyTo;
        $this->rate = $rate;
    }

    public function getCurrencyFrom(): CurrencyEnum
    {
        return $this->currencyFrom;
    }

    public function getCurrencyTo(): CurrencyEnum
    {
        return $this->currencyTo;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }
}
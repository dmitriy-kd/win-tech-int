<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
#[ORM\HasLifecycleCallbacks]
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

    #[ORM\Column]
    private DateTime $updatedAt;

    public function __construct(CurrencyEnum $currencyFrom, CurrencyEnum $currencyTo, int $rate)
    {
        $this->currencyFrom = $currencyFrom;
        $this->currencyTo = $currencyTo;
        $this->rate = $rate;
        $this->updatedAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
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

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
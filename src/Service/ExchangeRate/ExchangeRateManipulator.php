<?php

namespace App\Service\ExchangeRate;

use App\Entity\ExchangeRate;
use App\Enum\CurrencyEnum;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ExchangeRateManipulator
{
    public function __construct(private EntityManagerInterface $em) {}

    public function create(CurrencyEnum $from, CurrencyEnum $to, float $rate): ExchangeRate
    {
        $rate *= 100;
        $rate = (int) $rate;

        $exchangeRate = new ExchangeRate($from, $to, $rate);

        $this->em->persist($exchangeRate);
        $this->em->flush();

        return $exchangeRate;
    }

    public function updateRate(ExchangeRate $exchangeRate, float $rate): void
    {
        $rate *= 100;
        $exchangeRate->setRate((int) $rate);

        $this->em->flush();
    }
}
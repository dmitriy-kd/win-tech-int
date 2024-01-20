<?php

namespace App\Tests\Service\ExchangeRate;

use App\Entity\ExchangeRate;
use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use App\Service\ExchangeRate\AmountConverter;
use PHPUnit\Framework\TestCase;

class AmountConverterTest extends TestCase
{
    /** @test */
    public function convertRubToUsd()
    {
        $rate = 0.01 * 100;

        $exchangeRate = new ExchangeRate(
            CurrencyEnum::RUB,
            CurrencyEnum::USD,
            (int) $rate
        );

        $exchangeRepository = $this->getMockBuilder(ExchangeRateRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $exchangeRepository->method('find')
            ->willReturn($exchangeRate);

        $amountConverter = new AmountConverter($exchangeRepository);

        $oneHundredRubInCents = 100 * 100;

        $dollarsInCents = $amountConverter->convert(
            CurrencyEnum::RUB,
            CurrencyEnum::USD,
            $oneHundredRubInCents
        );

        $this->assertEquals(1, $dollarsInCents / 100);
    }

    /** @test */
    public function convertUsdToRub()
    {
        $rate = 89.43 * 100;

        $exchangeRate = new ExchangeRate(
            CurrencyEnum::USD,
            CurrencyEnum::RUB,
            (int) $rate
        );

        $exchangeRepository = $this->getMockBuilder(ExchangeRateRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $exchangeRepository->method('find')
            ->willReturn($exchangeRate);

        $amountConverter = new AmountConverter($exchangeRepository);

        $oneHundredDollarsInCents = 100 * 100;

        $rubsInCents = $amountConverter->convert(
            CurrencyEnum::USD,
            CurrencyEnum::RUB,
            $oneHundredDollarsInCents
        );

        $this->assertEquals(8943, $rubsInCents / 100);
    }
}
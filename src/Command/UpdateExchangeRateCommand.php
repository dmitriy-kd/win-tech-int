<?php

namespace App\Command;

use App\Entity\ExchangeRate;
use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use App\Service\ExchangeRate\ExchangeRateManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends Command
{
    public function __construct(
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly ExchangeRateManipulator $exchangeRateManipulator
    ) {
        parent::__construct('app:update-exchange-rate');
    }

    protected function configure(): void
    {
        $this->addOption('from', mode: InputOption::VALUE_REQUIRED, description: 'Currency from');
        $this->addOption('to', mode: InputOption::VALUE_REQUIRED, description: 'Currency to');
        $this->addOption('rate', mode: InputOption::VALUE_REQUIRED, description: 'Exchange rate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = CurrencyEnum::from(
            $input->getOption('from')
        );

        $to = CurrencyEnum::from(
            $input->getOption('to')
        );

        $rate = (float) $input->getOption('rate');

        if ($this->isIncorrectPrecision($rate)) {
            $output->writeln('Rate should have only two digits after the dot');

            return Command::INVALID;
        }

        /** @var ExchangeRate|null $exchangeRate */
        $exchangeRate = $this->exchangeRateRepository->find(['currencyFrom' => $from, 'currencyTo' => $to]);

        if ($exchangeRate) {
            $this->exchangeRateManipulator->updateRate($exchangeRate, $rate);
        } else {
            $this->exchangeRateManipulator->create($from, $to, $rate);
        }

        return Command::SUCCESS;
    }

    private function isIncorrectPrecision(float $num): bool
    {
        $scale = $num * 100;

        return str_contains((string) $scale, '.');
    }
}
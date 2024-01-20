<?php

namespace App\Command;

use App\Enum\CurrencyEnum;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetSumForRefundTransactionsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct('app:get-sum-for-refund-transactions');
    }

    protected function configure(): void
    {
        $this->addOption('for-days', mode: InputOption::VALUE_OPTIONAL, default: 7);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $forDays = (int) $input->getOption('for-days');

        if ($forDays < 1) {
            $output->writeln('For days must be positive');

            return Command::INVALID;
        }

        $dateFrom = new DateTime(
            sprintf('- %d days', $forDays)
        );

        foreach (CurrencyEnum::getList() as $currencyEnum) {
            $rsm = (new ResultSetMapping())->addScalarResult('sum', 'sum');

            $query = $this->em->createNativeQuery(
                "SELECT sum(amount) FROM wallet_transaction
                    WHERE created_at > ?
                    AND reason = 'refund'
                    AND currency = ?",
                $rsm
            );

            $query->setParameter(1, $dateFrom);
            $query->setParameter(2, $currencyEnum->value);

            $result = $query->getScalarResult();

            $output->writeln(
                sprintf(
                    'Result: %d %s',
                    $result[0]['sum'] / 100,
                    $currencyEnum->value
                )
            );
        }

        return Command::SUCCESS;
    }
}
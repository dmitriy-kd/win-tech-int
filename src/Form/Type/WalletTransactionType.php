<?php

namespace App\Form\Type;

use App\Entity\WalletTransaction;
use App\Enum\CurrencyEnum;
use App\Enum\WalletTransactionReasonEnum;
use App\Enum\WalletTransactionTypeEnum;
use App\EventSubscriber\FormatStringSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalletTransactionType extends AbstractType
{
    public function __construct(private readonly FormatStringSubscriber $formatStringSubscriber) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('type', EnumType::class, ['class' => WalletTransactionTypeEnum::class]);

        $builder->get('type')
            ->addEventSubscriber($this->formatStringSubscriber);

        $builder->add('amount', NumberType::class, ['scale' => 2]);

        $builder->add('currency', EnumType::class, ['class' => CurrencyEnum::class]);

        $builder->get('currency')
            ->addEventSubscriber($this->formatStringSubscriber);

        $builder->add('reason', EnumType::class, ['class' => WalletTransactionReasonEnum::class]);

        $builder->get('reason')
            ->addEventSubscriber($this->formatStringSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', WalletTransaction::class);
        $resolver->setDefault('csrf_protection', false);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
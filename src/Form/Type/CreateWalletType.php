<?php

namespace App\Form\Type;

use App\Entity\Wallet;
use App\Enum\CurrencyEnum;
use App\EventSubscriber\FormatStringSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('currency', EnumType::class, ['class' => CurrencyEnum::class]);

        $builder->get('currency')
            ->addEventSubscriber(new FormatStringSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Wallet::class);
        $resolver->setDefault('csrf_protection', false);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
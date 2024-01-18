<?php

namespace App\Form\Type;

use App\Entity\Wallet;
use App\Enum\CurrencyEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('currency', EnumType::class, ['class' => CurrencyEnum::class]);

        $builder->get('currency')
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $currency = $event->getData();
                $event->setData(strtolower($currency));
            });
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
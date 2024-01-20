<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'wallet', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(options: ['default' => 0])]
    private int $balance = 0;

    #[ORM\Column(length: 3, enumType: CurrencyEnum::class)]
    private ?CurrencyEnum $currency = null;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: WalletTransaction::class)]
    private Collection $transactions;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBalance(): int
    {
        if ($this->balance > 0) {
            return $this->balance / 100;
        }

        return $this->balance;
    }

    public function setBalance(int $balance): Wallet
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCurrency(): ?CurrencyEnum
    {
        return $this->currency;
    }

    public function setCurrency(CurrencyEnum $currency): Wallet
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTransactions(): ArrayCollection
    {
        return $this->transactions;
    }
}
<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use App\Enum\WalletTransactionReasonEnum;
use App\Enum\WalletTransactionTypeEnum;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WalletTransactionRepository;

#[ORM\Entity(repositoryClass: WalletTransactionRepository::class)]
class WalletTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private Wallet $wallet;

    #[ORM\Column(enumType: WalletTransactionTypeEnum::class)]
    private ?WalletTransactionTypeEnum $type = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 3, enumType: CurrencyEnum::class)]
    private ?CurrencyEnum $currency = null;

    #[ORM\Column(enumType: WalletTransactionReasonEnum::class)]
    private ?WalletTransactionReasonEnum $reason = null;

    #[ORM\Column]
    private DateTime $createdAt;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    public function getType(): ?WalletTransactionTypeEnum
    {
        return $this->type;
    }

    public function setType(WalletTransactionTypeEnum $type): WalletTransaction
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): WalletTransaction
    {
        $this->amount = $amount * 100;

        return $this;
    }

    public function getCurrency(): ?CurrencyEnum
    {
        return $this->currency;
    }

    public function setCurrency(CurrencyEnum $currency): WalletTransaction
    {
        $this->currency = $currency;

        return $this;
    }

    public function getReason(): ?WalletTransactionReasonEnum
    {
        return $this->reason;
    }

    public function setReason(WalletTransactionReasonEnum $reason): WalletTransaction
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
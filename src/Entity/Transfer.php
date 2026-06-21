<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'transfer')]
class Transfer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $uuid;

    #[ORM\Column(name: 'source_account_id')]
    private int $sourceAccountId;

    #[ORM\Column(name: 'destination_account_id')]
    private int $destinationAccountId;

    #[ORM\Column(type: 'decimal', precision: 18, scale: 2)]
    private string $amount;

    #[ORM\Column(name: 'source_balance_before', type: 'decimal', precision: 18, scale: 2)]
    private string $sourceBalanceBefore;

    #[ORM\Column(name: 'source_balance_after', type: 'decimal', precision: 18, scale: 2)]
    private string $sourceBalanceAfter;

    #[ORM\Column(name: 'destination_balance_before', type: 'decimal', precision: 18, scale: 2)]
    private string $destinationBalanceBefore;

    #[ORM\Column(name: 'destination_balance_after', type: 'decimal', precision: 18, scale: 2)]
    private string $destinationBalanceAfter;


    #[ORM\Column(length: 30)]
    private string $status;

    #[ORM\Column(name: 'idempotency_key', length: 197)]
    private string $idempotencyKey;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->uuid = bin2hex(random_bytes(16));
        $this->createdAt = new \DateTimeImmutable();
        $this->status = 'pending';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getSourceBalanceBefore(): string
    {
        return $this->sourceBalanceBefore;
    }

    public function setSourceBalanceBefore(int $balance): void
    {
        $this->sourceBalanceBefore = $balance;
    }


    public function getSourceBalanceAfter(): string
    {
        return $this->sourceBalanceAfter;
    }

    public function setSourceBalanceAfter(int $balance): void
    {
        $this->sourceBalanceAfter = $balance;
    }

    public function getDestinationBalanceBefore(): string
    {
        return $this->destinationBalanceBefore;
    }

    public function setDestinationBalanceBefore(int $balance): void
    {
        $this->destinationBalanceBefore = $balance;
    }

    public function getDestinationBalanceAfter(): string
    {
        return $this->destinationBalanceAfter;
    }

    public function setDestinationBalanceAfter(int $balance): void
    {
        $this->destinationBalanceAfter = $balance;
    }


    public function getSourceAccountId(): int
    {
        return $this->sourceAccountId;
    }

    public function setSourceAccountId(int $accountId): void
    {
        $this->sourceAccountId = $accountId;
    }

    public function getDestinationAccountId(): int
    {
        return $this->destinationAccountId;
    }

    public function setDestinationAccountId(int $accountId): void
    {
        $this->destinationAccountId = $accountId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function setIdempotencyKey(string $key): void
    {
        $this->idempotencyKey = $key;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

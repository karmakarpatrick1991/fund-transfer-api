<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36, unique: true)]
    private string $uuid;

    #[ORM\Column(type: 'decimal', precision: 18, scale: 2)]
    private string $balance = '0.00';

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->uuid = bin2hex(random_bytes(16));
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getUuid(): string { return $this->uuid; }

    public function getBalance(): string { return $this->balance; }

    public function setBalance(string $balance): void
    {
        $this->balance = $balance;
    }

    public function credit(string $amount): void
    {
        $this->balance = bcadd($this->balance, $amount, 2);
    }

    public function debit(string $amount): void
    {
        if (bccomp($this->balance, $amount, 2) < 0) {
            throw new \RuntimeException("Insufficient balance");
        }

        $this->balance = bcsub($this->balance, $amount, 2);
    }
}

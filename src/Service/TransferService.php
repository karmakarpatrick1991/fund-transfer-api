<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Transfer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\LockMode;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\RedisService;

class TransferService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RedisService           $redis
    )
    {
    }

    public function transfer(int $from, int $to, string $amount, string $key)
    {

        $this->em->beginTransaction();

        try {

            $redisKey = "idempotency:" . $key.$from.$to;

            $existingTransferUuid = $this->redis->get($redisKey);

            if ($existingTransferUuid) {
                throw new Exception("Seems like you are trying to do duplicate transaction.", 409);
            } else {
                $existing = $this->em
                    ->getRepository(Transfer::class)
                    ->findOneBy([
                        'idempotencyKey' => $key.$from.$to
                    ]);
            }
            if ($existing) {
                throw new Exception("Seems like you are trying to do duplicate transaction.", 409);
            }

            if (!is_numeric($amount)) {
                throw new Exception("Invalid amount value", 422);
            }

            if ($amount <= 0) {
                throw new Exception("Invalid amount, Amount must be greater than 0", 422);
            }

            if ($from === $to) {
                throw new Exception("Same account transfer is not allowed", 422);
            }

            $repo = $this->em->getRepository(Account::class);

            $source = $repo->findOneBy(['id' => $from]);
            $dest = $repo->findOneBy(['id' => $to]);

            if (!$source) {
                throw new Exception("Source Account not found", 404);
            }
            if (!$dest) {
                throw new Exception("Destination Account not found", 404);
            }

            // lock
            $this->em->lock($source, LockMode::PESSIMISTIC_WRITE);
            $this->em->lock($dest, LockMode::PESSIMISTIC_WRITE);

            if (bccomp($source->getBalance(), $amount, 2) < 0) {
                throw new Exception("Insufficient funds", 422);
            }
            $sourceBalanceBefore = $source->getBalance();
            $destinationBalanceBefore = $dest->getBalance();
            $source->debit($amount);
            $dest->credit($amount);
            $transfer = new Transfer();
            $transfer->setSourceAccountId($from);
            $transfer->setDestinationAccountId($to);
            $transfer->setAmount($amount);
            $transfer->setIdempotencyKey($key.$from.$to);
            $transfer->setSourceBalanceBefore($sourceBalanceBefore);
            $transfer->setSourceBalanceAfter($source->getBalance());
            $transfer->setDestinationBalanceBefore($destinationBalanceBefore);
            $transfer->setDestinationBalanceAfter($dest->getBalance());
            $transfer->setStatus('completed');

            $this->em->persist($transfer);
            $this->em->flush();

            $this->em->commit();
            $this->redis->set(
                $redisKey,
                (string)$transfer->getUuid(),
                86400

            );
            $this->redis->delete('account:' . $source->getUuid());
            $this->redis->delete('account:' . $dest->getUuid());
            $this->redis->set(
                'account:' . $source->getUuid(),
                json_encode([
                    'status' => 200,
                    'error' => null,
                    'message' => [
                        'account_no' => $source->getId(),
                        'reference_no' => $source->getUuid(),
                        'balance' => $source->getBalance(),
                        'message' => 'Balance updated successfully.'
                    ]
                ]),
                300
            );
            $this->redis->set(
                'account:' . $dest->getUuid(),
                json_encode([
                    'status' => 200,
                    'error' => null,
                    'message' => [
                        'account_no' => $dest->getId(),
                        'reference_no' => $dest->getUuid(),
                        'balance' => $dest->getBalance(),
                        'message' => 'Balance updated successfully.'
                    ]
                ]),
                300
            );

            return $transfer;

        } catch (\Throwable $e) {
//            dd(
//                get_class($e),
//                $e->getMessage(),
//                $e->getTraceAsString()
//            );
            $this->em->rollback();
            throw new \Exception(
                json_encode([
                    'error' => $e->getMessage(),
                    'code' => $e->getCode() ?: 500,
                ]),
                $e->getCode() ?: 500,
                $e
            );
        }
    }

    private function validate(string $from, string $to, string $amount): void
    {
        if (bccomp($amount, '0', 2) <= 0) {
            throw new \InvalidArgumentException("Amount must be > 0");
        }
        if ($from === $to) {
            throw new \InvalidArgumentException("Same account transfer not allowed");
        }

    }
}

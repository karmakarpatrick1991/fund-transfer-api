<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Service\RedisService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private RedisService $redis,
        private readonly LoggerInterface $logger
    )
    {
    }

    #[Route('/app/create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $data = $request->getContent() ? json_decode($request->getContent(), true) : null;
        if (empty($data)) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'MISSING_ACCOUNT_PAYLOAD',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Account Payload cannot be empty'
                ]
            ]);
        }

        if (!isset($data['initial_balance'])) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'INVALID_BALANCE',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Initial Balance Parameter (initial_balance) key is required'
                ]
            ]);
        }

        if (!is_numeric($data['initial_balance'])) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'INVALID_BALANCE_VALUE',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Balance Value must be a number and greater than  or equal to 0'
                ]
            ]);
        }

        if ($data['initial_balance'] < 0) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'MINIMUM_REQUIRED_BALANCE',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Minimum Balance cannot be less than zero to create an account.'
                ]
            ]);
        }
        $this->logger->info('Account creation initiated', [
            'message' => json_encode($data)
        ]);
        $account = new Account();
        $account->setBalance($data['initial_balance']);
        $em->persist($account);
        $em->flush();
        $this->logger->info('Account creation completed', [
            'message' => json_encode($data),
            'uuid' => $account->getUuid()
        ]);
        return new JsonResponse([
            'status' => 200,
            'error' => null,
            'message' => [
                'account_no' => $account->getId(),
                'reference_no' => $account->getUuid(),
                'message' => 'Account created successfully with balance: ' . $account->getBalance()
            ]
        ]);
    }

    #[Route('/app/account/{uuid}', methods: ['GET'])]
    public function get(string $uuid, AccountRepository $repo)
    {
        if (!isset($uuid)) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'MISSING_UUID_REFERENCE',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Account creation reference number cannot be empty.'
                ]
            ]);
        }
        $accountCacheKey = 'account:' . $uuid;
        $accountInfoFromCache = $this->redis->get($accountCacheKey);
        if (!empty($accountInfoFromCache)) {
            return new JsonResponse(
                json_decode($accountInfoFromCache)
            );
        } else {
            $account = $repo->findOneBy(['uuid' => $uuid]);
        }
        if (!$account) {
            return new JsonResponse([
                'status' => 404,
                'error' => 'ACCOUNT_NOT_FOUND',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Account not found.'
                ]
            ]);
        }
        $message = [
            'status' => 200,
            'error' => null,
            'message' => [
                'account_no' => $account->getId(),
                'reference_no' => $account->getUuid(),
                'balance' => $account->getBalance(),
                'message' => 'Account info successfully retrieved.'
            ]];
        $this->redis->set(
            'account:' . $uuid,
            json_encode($message),
            300
        );
        return new JsonResponse($message);
    }

    #[Route('/app/update/{uuid}', methods: ['PUT'])]
    public function update(string $uuid, Request $request, AccountRepository $repo, EntityManagerInterface $em)
    {
        if (!isset($uuid)) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'MISSING_UUID_REFERENCE',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Account creation reference number cannot be empty.'
                ]
            ]);
        }
        $account = $repo->findOneBy(['uuid' => $uuid]);
        if (!$account) {
            return new JsonResponse([
                'status' => 404,
                'error' => 'ACCOUNT_NOT_FOUND',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Account number does not exists.'
                ]
            ]);
        }
        $data = $request->getContent() ? json_decode($request->getContent(), true) : null;
        if (empty($data)) {
            return new JsonResponse([
                'status' => 400,
                'error' => 'MISSING_BALANCE_PAYLOAD',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Invalid Balance Payload.'
                ]
            ]);
        }
        if (isset($data['balance']) && is_numeric($data['balance']) && $data['balance'] >= 0) {
            $this->logger->info('Account updated initiated', [
                'message' => json_encode($data),
                'uuid' => $account->getUuid()
            ]);
            $account->setBalance($data['balance']);
            $this->logger->info('Account updated completed', [
                'message' => json_encode($data),
                'uuid' => $account->getUuid()
            ]);
            $this->redis->delete('account:' . $uuid,);
            $this->redis->set(
                'account:' . $uuid,
                    json_encode([
                        'status' => 200,
                        'error' => null,
                        'message' => [
                            'account_no' => $account->getId(),
                            'reference_no' => $account->getUuid(),
                            'balance' => $account->getBalance(),
                            'message' => 'Balance updated successfully.'
                        ]
                    ]),
                300
            );
        } else {
            return new JsonResponse([
                'status' => 400,
                'error' => 'INVALID_BALANCE_PAYLOAD',
                'message' => [
                    'account_no' => null,
                    'reference_no' => null,
                    'message' => 'Invalid Balance Payload/Balance must be numeric and greater than 0'
                ]
            ]);
        }
        $em->flush();
        return new JsonResponse([
            'status' => 200,
            'error' => null,
            'message' => [
                'account_no' => $account->getId(),
                'reference_no' => $account->getUuid(),
                'balance' => $account->getBalance(),
                'message' => 'Balance updated successfully.'
            ]
        ]);
    }
}

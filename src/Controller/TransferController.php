<?php

namespace App\Controller;

use App\Service\RedisService;
use App\Service\TransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class TransferController extends AbstractController
{
    public function __construct(
        private RedisService $redis
    )
    {
    }

    #[Route('/app/transfer', methods: ['POST'])]
    public function transfer(Request $req, TransferService $service)
    {
        $request_payload = $req->getContent() ? json_decode($req->getContent(), true) : null;
        if (empty($request_payload)) {
            return new JsonResponse([
                'status' => 400,
                "error" => "MISSING_PAYLOAD",
                "message" => "Payload information containing account details is required for fund transfers."
            ]);
        }
        $idempotency_key = $req->headers->get('idempotency-key') ? $req->headers->get('idempotency-key') : null;
        if (empty($idempotency_key)) {
            return new JsonResponse([
                'status' => 400,
                "error" => "MISSING_IDEMPOTENCY_KEY",
                "message" => "Idempotency-Key is required for fund transfers."
            ]);
        }
        try {
            $transfer = $service->transfer(
                $request_payload['source_account_id'],
                $request_payload['destination_account_id'],
                $request_payload['amount'],
                $idempotency_key
            );
        } catch (\Exception $e) {
            $error_details = json_decode($e->getMessage(),true);
            return new JsonResponse([
                'status' => $error_details['code'] ?? 400,
                'error' => $error_details['error'] ?? '',
                'message' => 'Please try again by clearing the mentioned error messages.'
            ]);
        }

        return new JsonResponse([
            'status' => 200,
            "error" => null,
            "message" => "Fund transfer completed successfully with reference number: ".$transfer->getUuid()
        ]);
    }
}

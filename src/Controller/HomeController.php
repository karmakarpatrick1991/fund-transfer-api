<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $html = file_get_contents(__DIR__ . '/../../public/home.php');
        return new Response($html, 200, [
            'Content-Type' => 'text/html',
        ]);
    }

}

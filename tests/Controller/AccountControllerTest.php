<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AccountControllerTest extends WebTestCase
{
    public function testCreateAccount(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/app/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'initial_balance' => '1000.00',
            ])
        );

        $response = $client->getResponse();

        self::assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        self::assertIsArray($data);
        self::assertArrayHasKey('uuid', $data);
        self::assertEquals('1000.00', $data['balance']);
    }

    public function testGetUnknownAccount(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/app/account/non-existent-uuid'
        );

        self::assertResponseStatusCodeSame(404);
    }
}

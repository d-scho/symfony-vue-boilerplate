<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test doesn't work :(
 * See bin/test_auth instead.
 *
 * It seems routes are checked before firewalls for the test client, hence it says a controller is missing on the login endpoint.
 */
class AuthenticationTest extends WebTestCase
{
    public function test_missing_credentials(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('Invalid JSON.', $data['error']);

        $client->request('POST', '/api/login', content: json_encode([
            'username' => 'admin',
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('The key "password" must be provided.', $data['error']);

        $client->request('POST', '/api/login', content: json_encode([
            'password' => 'secret',
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame('The key "username" must be provided.', $data['error']);
    }

    public function test_invalid_credentials(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', content: json_encode([
            'username' => 'admin',
            'password' => 'wrong-password',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertSame('Bad credentials.', $data['error']);
    }

    public function test_successful_token_return(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', content: json_encode([
            'username' => 'admin',
            'password' => 'admin-pw',
        ]));

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertNotEmpty($data['token']);
    }
}

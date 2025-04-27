<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationTest extends WebTestCase
{
    public function test_get_CSRF_token(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('csrfToken', $data);
        $this->assertNotEmpty($data['csrfToken'], 'CSRF token should not be empty');
    }

    public function test_missing_CSRF_token(): void
    {
        $client = static::createClient();

        $client->request('POST', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame($data['error'], 'Missing CSRF token. Provide it as "X-CSRF-Token" header.');
    }

    public function test_invalid_CSRF_token(): void
    {
        $client = static::createClient();

        $client->request('POST', '/login', server: [
            'HTTP_X-CSRF-TOKEN' => 'invalid',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame($data['error'], 'Invalid CSRF token.');
    }

    public function test_missing_credentials(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $client->request('POST', '/login', server: [
            'HTTP_X-CSRF-TOKEN' => $csrfToken,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame(
            $data['error'],
            'Username and/or password are wrongly formatted or missing. Provide "username" and "password" fields as strings as JSON.'
        );
    }

    public function test_invalid_credentials(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $client->request(
            'POST',
            '/login',
            server: [
                'HTTP_X-CSRF-TOKEN' => $csrfToken,
            ],
            content: json_encode([
                'username' => 'invalid',
                'password' => 'invalid',
            ]),
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertSame($data['error'], 'Bad credentials.');
    }

    public function test_successful_login(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $client->request(
            'POST',
            '/login',
            server: [
                'HTTP_X-CSRF-TOKEN' => $csrfToken,
            ],
            content: json_encode([
                'username' => 'admin',
                'password' => 'admin-pw',
            ]),
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    private function getCsrfToken(KernelBrowser $client): string
    {
        $client->request('GET', '/login');

        $data = json_decode($client->getResponse()->getContent(), true);

        return is_array($data) ? ($data['csrfToken'] ?? '') : '';
    }
}

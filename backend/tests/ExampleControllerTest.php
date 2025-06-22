<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use SymfonyVueBoilerplateBackend\API\DTO\ExampleCreationDTO;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;
use SymfonyVueBoilerplateBackend\Kernel;

final class ExampleControllerTest extends WebTestCase
{
    public function test_get_examples_returns_401_on_unauthenticated_request(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->jsonRequest('GET', '/api/examples');

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $content['code']);
        self::assertSame('JWT Token not found', $content['message']);
    }

    public function test_get_examples_returns_403_on_unauthorized_request(): void
    {
        $this->expectOutputRegex('/^on controller" at ExceptionListener.php/');

        self::ensureKernelShutdown();
        $client = $this->createUserClient();

        $client->jsonRequest('GET', '/api/examples');

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        self::assertSame(Response::HTTP_FORBIDDEN, $content['status']);
        self::assertSame('An error occurred', $content['title']);
        self::assertSame('Forbidden', $content['detail']);
    }

    public function test_get_examples(): void
    {
        self::ensureKernelShutdown();
        $client = $this->createAdminClient();

        $client->jsonRequest('GET', '/api/examples');

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertSame([
            [
                'uuid' => '00000000-0000-0000-0000-000000000001',
                'title' => 'Example title',
            ]
        ], $content);
    }

    public function test_post_examples_returns_401_on_unauthenticated_request(): void
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/examples', [
            'title' => 'My example'
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $content['code']);
        self::assertSame('JWT Token not found', $content['message']);
    }

    public function test_post_examples_returns_403_on_unauthorized_request(): void
    {
        $this->expectOutputRegex('/^on controller" at ExceptionListener.php/');

        self::ensureKernelShutdown();
        $client = $this->createUserClient();

        $client->jsonRequest('POST', '/api/examples', [
            'title' => 'My example'
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        self::assertSame(Response::HTTP_FORBIDDEN, $content['status']);
        self::assertSame('An error occurred', $content['title']);
        self::assertSame('Forbidden', $content['detail']);
    }

    public function test_post_examples(): void
    {
        self::ensureKernelShutdown();
        $client = $this->createAdminClient();

        $client->jsonRequest('POST', '/api/examples', [
            'title' => 'My example'
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertSame('00000000-0000-0000-0000-000000000002', $content['uuid']);
        self::assertSame('My example', $content['title']);
    }

    private function createUserClient(): KernelBrowser
    {
        return $this->createClientWithBearerToken('user', 'user-pw');
    }

    private function createAdminClient(): KernelBrowser
    {
        return $this->createClientWithBearerToken('admin', 'admin-pw');
    }

    private function createClientWithBearerToken(string $username, string $password): KernelBrowser
    {
        $client = self::createClient();

        $client->jsonRequest(
            'POST',
            '/api/login',
            [
                'username' => $username,
                'password' => $password,
            ],
        );

        /** @var array{token: string} $data */
        $data = json_decode($client->getResponse()->getContent(), true);

        $token = $data['token'];

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));

        return $client;
    }
}

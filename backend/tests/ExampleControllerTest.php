<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;

final class ExampleControllerTest extends WebTestCase
{
    private string $userToken;

    private string $adminToken;

    private KernelBrowser $client;

//    protected function setUp(): void
//    {
//        self::ensureKernelShutdown();
//        $this->client = self::createClient();
//
//        $this->client->request(
//            method: 'POST',
//            uri: '/api/login',
//            server: ['CONTENT_TYPE' => 'application/json'],
//            content: json_encode([
//                'username' => 'user',
//                'password' => 'user-pw',
//            ]),
//        );
//        $response = $this->client->getResponse();
//        /** @var array{token: string} $data */
//        $data = json_decode($response->getContent());
////        dd($data);
//        $this->userToken = $data['token'];

//        $this->client->request(
//            method: 'POST',
//            uri: '/api/login',
//            server: ['CONTENT_TYPE' => 'application/json'],
//            content: json_encode([
//                'username' => 'admin',
//                'password' => 'admin-pw',
//            ]),
//        );
//        $response = $this->client->getResponse();
//        /** @var array{token: string} $data */
//        $data = json_decode($response->getContent());
//        $this->adminToken = $data['token'];
//    }

    public function test_example(): void
    {
//        self::ensureKernelShutdown();
        $this->client = self::createClient();

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/api/login',
            parameters: [
                'username' => 'user',
                'password' => 'user-pw',
            ],
            server: ['CONTENT_TYPE' => 'application/json'],
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class LoginTest extends WebTestCase
{
    public static function missingLoginParamsProvider(): iterable
    {
        yield 'missing body content' => [
            'payload' => [],
            'expectedCode' => Response::HTTP_BAD_REQUEST,
            'expectedContent' => [
                'status' => Response::HTTP_BAD_REQUEST,
                'title' => 'An error occurred',
                'detail' => 'Bad Request',
            ],
        ];

        yield 'missing password' => [
            'payload' => ['username' => 'admin'],
            'expectedCode' => Response::HTTP_BAD_REQUEST,
            'expectedContent' => [
                'status' => Response::HTTP_BAD_REQUEST,
                'title' => 'An error occurred',
                'detail' => 'Bad Request',
            ],
        ];

        yield 'missing username' => [
            'payload' => ['password' => 'secret'],
            'expectedCode' => Response::HTTP_BAD_REQUEST,
            'expectedContent' => [
                'status' => Response::HTTP_BAD_REQUEST,
                'title' => 'An error occurred',
                'detail' => 'Bad Request',
            ],
        ];
    }

    #[DataProvider('missingLoginParamsProvider')]
    public function test_missing_request_params_for_login(
        array $payload,
        int $expectedCode,
        array $expectedContent
    ): void {
        $this->expectOutputRegex('/^Uncaught PHP Exception Symfony\\\\Component\\\\HttpKernel\\\\Exception\\\\BadRequestHttpException:/');

        $client = self::createClient();

        $client->jsonRequest('POST', '/api/login', $payload);

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertSame($expectedCode, $content['status']);
        foreach ($expectedContent as $key => $expectedValue) {
            self::assertSame($expectedValue, $content[$key]);
        }
    }

    public function test_invalid_credentials(): void
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/login', [
            'username' => 'admin',
            'password' => 'wrong-password',
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $content['code']);
        self::assertSame('Invalid credentials.', $content['message']);
    }

    public function test_successful_token_return(): void
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/login', [
            'username' => 'admin',
            'password' => 'admin-pw',
        ]);

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        self::assertNotEmpty($content['token']);
    }
}

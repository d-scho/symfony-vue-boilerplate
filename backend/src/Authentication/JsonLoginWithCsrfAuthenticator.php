<?php

namespace Api\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

final class JsonLoginWithCsrfAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $request->getPathInfo() === '/login';
    }

    public function authenticate(Request $request): Passport
    {
        $csrfToken = $request->headers->get('X-CSRF-Token');

        if ($csrfToken === null) {
            throw new AuthenticationException('Missing CSRF token. Provide it as "X-CSRF-Token" header.');
        }

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            throw new AuthenticationException('Invalid CSRF token.');
        }

        $data = json_decode($request->getContent(), true);

        try {
            assert(is_array($data), new \InvalidArgumentException());
            assert(is_string($data['username'] ?? null), new \InvalidArgumentException());
            assert(is_string($data['password'] ?? null), new \InvalidArgumentException());
        } catch (\InvalidArgumentException) {
            throw new AuthenticationException(
                'Username and/or password are wrongly formatted or missing. Provide "username" and "password" fields as strings as JSON.'
            );
        }

        /** @var string $username */
        $username = $data['username'];

        /** @var string $password */
        $password = $data['password'];

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
    }
}

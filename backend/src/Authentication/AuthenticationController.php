<?php

declare(strict_types=1);

namespace Api\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final readonly class AuthenticationController
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    #[Route(path: '/login', name: 'app_csrf_token', methods: 'GET')]
    public function csrfToken(): JsonResponse
    {
        $token = $this->csrfTokenManager->getToken('authenticate')->getValue();

        return new JsonResponse([
            'csrfToken' => $token,
        ]);
    }
}

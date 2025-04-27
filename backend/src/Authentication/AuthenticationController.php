<?php

declare(strict_types=1);

namespace DScho\Backend\Authentication;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class AuthenticationController
{
    /**
     * Used to request a CSRF token for login.
     *
     * Login POST is handled by 'main' firewall, see `config/packages/security.php`.
     * Authentication success is handled by {@see JsonNoContentSuccessHandler}.
     */
    #[Route(name: 'app_csrf_token', path: '/login', methods: 'GET')]
    public function index(): Response
    {
        return new Response('foo');
    }
}

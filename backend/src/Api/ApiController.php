<?php

declare(strict_types=1);

namespace DScho\Backend\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class ApiController
{
    #[Route(name: 'app_index', path: '/', methods: 'GET')]
    public function index(): Response
    {
        return new Response('foo');
    }
}

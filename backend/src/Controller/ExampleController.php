<?php

declare(strict_types=1);

namespace Api\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class ExampleController
{
    #[Route(name: 'app_index', path: '/', methods: 'GET')]
    public function index(): Response
    {
        return new Response('access');
    }
}

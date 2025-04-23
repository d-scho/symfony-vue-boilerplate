<?php

declare(strict_types=1);

namespace DScho\Backend;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class TestController
{
    #[Route(name: 'app_index', path: '/', methods: 'GET')]
    public function index(): Response
    {
        return new Response('foo');
    }

    #[Route(name: 'app_test', path: '/test', methods: 'GET')]
    public function test(): Response
    {
        return new Response('bar');
    }
}

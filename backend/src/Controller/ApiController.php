<?php

declare(strict_types=1);

namespace Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/api', name: 'api_')]
final class ApiController extends AbstractController
{
    #[Route(path: '/user', name: 'user_resource', methods: 'GET')]
    #[IsGranted('ROLE_USER')]
    public function user(): JsonResponse
    {
        return $this->json(['userdata' => true]);
    }

    #[Route(path: '/admin', name: 'user_resource', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(): JsonResponse
    {
        return $this->json(['admindata' => true]);
    }
}

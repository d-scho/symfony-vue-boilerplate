<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyVueBoilerplateBackend\Authentication\Provider\CustomUserProvider;

#[Route(path: '/api', name: 'api_')]
final class ApiController extends AbstractController
{
    #[Route(path: '/users', name: 'users_all', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function usersAll(CustomUserProvider $customUserProvider): JsonResponse
    {
        return $this->json($customUserProvider->getAllUsersAsViewModel());
    }
}

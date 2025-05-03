<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyVueBoilerplateBackend\API\Request\UserCreationRequest;
use SymfonyVueBoilerplateBackend\API\Request\UserUpdateRequest;
use SymfonyVueBoilerplateBackend\API\ViewModel\UserViewModel;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;

#[Route(path: '/api/users', name: 'api_users_')]
#[IsGranted('ROLE_ADMIN')]
final class UsersController extends AbstractController
{
    #[Route(path: '/', name: 'show_all', methods: 'GET')]
    public function showAll(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->getAllAsViewModels());
    }

    #[Route(path: '/{id}', name: 'show_single', methods: 'GET')]
    public function showSingle(#[MapEntity] User $user): JsonResponse
    {
        return $this->json(new UserViewModel(
            $user->uuid,
            $user->username,
            $user->displayName,
        ));
    }

    #[Route(path: '/', name: 'create', methods: 'POST')]
    public function create(
        #[MapRequestPayload] UserCreationRequest $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $user = new User(
            Uuid::uuid4()->toString(),
            $request->username,
            '',
            $request->displayName,
            [
                'ROLE_USER',
            ],
        );

        $user->setPassword($passwordHasher->hashPassword($user, $request->password));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            new UserViewModel(
                $user->uuid,
                $user->username,
                $user->displayName,
            ),
        );
    }

    #[Route(path: '/{id}', name: 'update', methods: 'PUT')]
    public function update(
        #[MapEntity] User $user,
        #[MapRequestPayload] UserUpdateRequest $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $user->updatedWith(
            $passwordHasher,
            $request->username,
            $request->password,
            $request->displayName,
        );

        // TODO: does not work with new instance maybe?
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            new UserViewModel(
                $user->uuid,
                $user->username,
                $user->displayName,
            ),
        );
    }

    #[Route(path: '/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(
        #[MapEntity] User $user,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_ACCEPTED);
    }
}

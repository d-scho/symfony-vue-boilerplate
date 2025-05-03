<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\Provider;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;

/**
 * @implements UserProviderInterface<User>
 */
final readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @param User $user
     */
    public function refreshUser(UserInterface $user): User
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneBy(['username' => $identifier])
            ?? throw new UserNotFoundException();
    }
}

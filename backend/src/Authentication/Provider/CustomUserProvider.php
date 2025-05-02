<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\Provider;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use SymfonyVueBoilerplateBackend\Authentication\ValueObject\CustomUser;

/**
 * @implements UserProviderInterface<CustomUser>
 */
class CustomUserProvider implements UserProviderInterface
{
    /**
     * @var array<CustomUser>
     */
    private array $users;

    public function __construct()
    {
        $this->users = [
            new CustomUser(
                'admin',
                '$2y$13$WvFzNg4QGQBMjjOjQ.I9N.X5VqZLpSyUAdkHheuL4rbpoWruc/wPi', // 'admin-pw'
                'Administrator',
                [
                    'ROLE_ADMIN',
                    'ROLE_USER',
                ],
            ),
            new CustomUser(
                'user',
                '$2y$13$E7Bfwsw/0Q0LQBkl3nWJ3OMPzri2BNEht.wZ69ylN6zDrcCPFI8UO', // 'user-pw'
                'Generic user',
                [
                    'ROLE_USER',
                ],
            ),
        ];
    }

    /**
     * @param CustomUser $user
     */
    public function refreshUser(UserInterface $user): CustomUser
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === CustomUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return array_find($this->users, static fn (CustomUser $user) => $user->getUserIdentifier() === $identifier)
            ?? throw new UserNotFoundException();
    }
}

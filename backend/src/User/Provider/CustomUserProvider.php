<?php

declare(strict_types=1);

namespace Api\User\Provider;

use Api\User\ValueObject\CustomUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

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
                'admin-pw',
                'Administrator',
                [
                    'ROLE_ADMIN',
                    'ROLE_USER',
                ],
            ),
            new CustomUser(
                'user',
                'user-pw',
                'Generic user',
                [
                    'ROLE_USER',
                ],
            ),
        ];
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return $class === CustomUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return array_find($this->users, static fn (CustomUser $user) => $user->getUserIdentifier() === $identifier);
    }
}
<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CustomUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param non-empty-string $username,
     * @param array<string> $roles,
     */
    public function __construct(
        public UuidInterface $uuid,
        public readonly string $username,
        private string $password,
        public readonly string $displayName,
        public readonly array $roles,
    ) {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        $this->password = '[redacted]';
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

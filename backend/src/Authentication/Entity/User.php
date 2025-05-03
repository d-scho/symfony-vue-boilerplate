<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;


#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'user')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param non-empty-string $username,
     * @param array<string> $roles,
     */
    public function __construct(
        #[Id]
        #[Column(type: 'string', length: 36, unique: true)]
        public string $uuid,
        #[Column(type: 'string', length: 64, unique: true)]
        public readonly string $username,
        #[Column]
        private string $password,
        #[Column(type: 'string', length: 64, unique: true)]
        public readonly string $displayName,
        #[Column]
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

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}

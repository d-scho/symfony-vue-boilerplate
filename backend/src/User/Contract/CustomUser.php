<?php

declare(strict_types=1);

namespace Api\User\Contract;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface CustomUser
{
    public string $displayName { get; }
}
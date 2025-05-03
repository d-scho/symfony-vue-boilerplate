<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\ViewModel;

final readonly class UserViewModel
{
    public function __construct(
        public string $id,
        public string $username,
        public string $displayName,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\ViewModel;

final readonly class CustomUserViewModel
{
    public function __construct(
        public string $username,
        public string $displayName,
    ) {
    }
}

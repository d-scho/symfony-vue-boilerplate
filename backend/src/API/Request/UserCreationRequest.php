<?php

namespace SymfonyVueBoilerplateBackend\API\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCreationRequest
{
    public function __construct(
        #[NotBlank]
        #[Length(min: 2, max: 64)]
        public string $username,

        #[NotBlank]
        #[Length(min: 6, max: 64)]
        public string $password,

        #[NotBlank]
        #[Length(min: 2, max: 64)]
        public string $displayName,
    ) {
    }
}
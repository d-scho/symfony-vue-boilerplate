<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\DTO;

use OpenApi\Attributes\Property;
use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class ExampleCreationDTO
{
    public function __construct(
        #[NotBlank]
        #[Property(example: 'some string')]
        public string $title,
    ) {
    }
}
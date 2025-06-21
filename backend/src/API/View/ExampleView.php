<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\View;

use OpenApi\Attributes\Property;
use Ramsey\Uuid\Uuid;

final readonly class ExampleView
{
    public function __construct(
        #[Property(example: '92bb0eec-4852-451e-8edc-ec884077b256')]
        public string $uuid,
        #[Property(example: 'some string')]
        public string $title,
    ) {
    }
}
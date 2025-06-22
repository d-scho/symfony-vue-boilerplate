<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\API\Controller;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Tag;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyVueBoilerplateBackend\API\DTO\ExampleCreationDTO;
use SymfonyVueBoilerplateBackend\API\View\ExampleView;

#[Tag('examples')]
#[\OpenApi\Attributes\Response(
    response: Response::HTTP_UNAUTHORIZED,
    description: 'Unauthenticated request.',
)]
#[\OpenApi\Attributes\Response(
    response: Response::HTTP_FORBIDDEN,
    description: 'Insufficient permissions.',
)]
#[Route(path: '/api/examples', name: 'api_examples_')]
#[IsGranted('ROLE_ADMIN')]
final class ExampleController extends AbstractController
{
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Example created.',
        content: new JsonContent(
            ref: new Model(type: ExampleView::class),
            type: 'object',
        ),
    )]
    #[Route(name: 'example_index', methods: ['GET'], format: 'json')]
    public function getAll(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }

    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_CREATED,
        description: 'Example created.',
        content: new JsonContent(
          ref: new Model(type: ExampleView::class),
          type: 'object',
        ),
    )]
    #[\OpenApi\Attributes\Response(
        response: Response::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Unprocessable entity.',
    )]
    #[Route(name: 'example_create', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload] ExampleCreationDTO $dto,
    ): JsonResponse {
        return new JsonResponse(new ExampleView(
            Uuid::uuid4()->toString(),
            $dto->title,
        ), Response::HTTP_CREATED);
    }
}

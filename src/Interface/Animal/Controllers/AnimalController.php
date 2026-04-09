<?php

namespace Source\Interface\Animal\Controllers;

use Illuminate\Http\JsonResponse;
use Ramsey\Uuid\Uuid;
use Source\Application\Animal\UseCases\AnimalCreateUseCase;
use Source\Application\Animal\UseCases\AnimalDestroyUseCase;
use Source\Application\Animal\UseCases\AnimalGetByIdUseCase;
use Source\Application\Animal\UseCases\AnimalGetBySlugUseCase;
use Source\Application\Animal\UseCases\AnimalIndexUseCase;
use Source\Application\Animal\UseCases\AnimalPublishUseCase;
use Source\Application\Animal\UseCases\AnimalStatusUpdateUseCase;
use Source\Application\Animal\UseCases\AnimalUnpublishUseCase;
use Source\Application\Animal\UseCases\AnimalUpdateUseCase;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Interface\Animal\Requests\AnimalIndexRequest;
use Source\Interface\Animal\Requests\AnimalStatusUpdateRequest;
use Source\Interface\Animal\Requests\AnimalStoreRequest;
use Source\Interface\Animal\Requests\AnimalUpdateRequest;

final class AnimalController
{
    public function index(
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $responseDTO = $animalIndexUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json($responseDTO);
    }

    public function indexByType(
        string $type,
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $responseDTO = $animalIndexUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json($responseDTO);
    }

    public function getById(
        string $id,
        AnimalGetByIdUseCase $animalGetByIdUseCase
    ): JsonResponse {
        $responseDTO = $animalGetByIdUseCase->apply(
            id: Uuid::fromString($id),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_OK,
        );
    }

    public function getBySlug(
        string $type,
        string $slug,
        AnimalGetBySlugUseCase $animalGetBySlugCase
    ): JsonResponse {
        $responseDTO = $animalGetBySlugCase->apply(
            type: AnimalType::tryFrom($type),
            slug: StringValueObject::fromString($slug),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_OK,
        );
    }

    public function store(
        AnimalStoreRequest $request,
        AnimalCreateUseCase $animalCreateUseCase,
    ): JsonResponse {
        $responseDTO = $animalCreateUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_CREATED,
        );
    }

    public function update(
        AnimalUpdateRequest $request,
        string $id,
        AnimalUpdateUseCase $animalUpdateUseCase,
    ): JsonResponse {
        $responseDTO = $animalUpdateUseCase->apply(
            id: Uuid::fromString($id),
            dto: $request->getDTO(),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function statusUpdate(
        string $id,
        AnimalStatusUpdateRequest $request,
        AnimalStatusUpdateUseCase $animalStatusUpdateUseCase
    ): JsonResponse {
        $responseDTO = $animalStatusUpdateUseCase->apply(
            id: Uuid::fromString($id),
            dto: $request->getDTO(),
        );

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function publish(
        string $id,
        AnimalPublishUseCase $animalPublishUseCase
    ): JsonResponse {
        $responseDTO = $animalPublishUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function unpublish(
        string $id,
        AnimalUnpublishUseCase $animalUnpublishUseCase
    ): JsonResponse {
        $responseDTO = $animalUnpublishUseCase->apply(Uuid::fromString($id));

        return response()->json(
            $responseDTO,
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function destroy(
        string $id,
        AnimalDestroyUseCase $animalDestroyUseCase
    ): JsonResponse {
        $animalDestroyUseCase->apply(Uuid::fromString($id));

        return response()->json(
            [],
            JsonResponse::HTTP_NO_CONTENT,
        );
    }
}

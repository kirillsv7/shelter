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
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Interface\Animal\Mappers\AnimalMapper;
use Source\Interface\Animal\Requests\AnimalIndexRequest;
use Source\Interface\Animal\Requests\AnimalStatusUpdateRequest;
use Source\Interface\Animal\Requests\AnimalStoreRequest;
use Source\Interface\Animal\Requests\AnimalUpdateRequest;
use Source\Interface\Shared\Mappers\PaginationMapper;
use Throwable;

final class AnimalController
{
    public function __construct(
        protected AnimalMapper $animalMapper,
        protected PaginationMapper $paginationMapper,
    ) {
    }

    public function index(
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $responseDTO = $animalIndexUseCase->apply(
            name: $request->getName(),
            type: $request->getType(),
            gender: $request->getGender(),
            ageMin: $request->getAgeMin(),
            ageMax: $request->getAgeMax(),
            limit: $request->getLimit(),
            page: $request->getPage(),
        );

        return response()->json([
            'animals' => array_map(
                fn ($animal) => $this->animalMapper->toArray($animal),
                $responseDTO->animals,
            ),
            'pagination' => $this->paginationMapper->toArray($responseDTO->pagination),
        ]);
    }

    public function indexByType(
        string $type,
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $type = AnimalType::single($type);

        $result = $animalIndexUseCase->apply(
            name: $request->getName(),
            type: $type,
            gender: $request->getGender(),
            ageMin: $request->getAgeMin(),
            ageMax: $request->getAgeMax(),
            limit: $request->getLimit(),
            page: $request->getPage(),
        );

        return response()->json($result);
    }

    /**
     * @throws Throwable
     */
    public function store(
        AnimalStoreRequest $request,
        AnimalCreateUseCase $animalCreateUseCase,
    ): JsonResponse {
        $animal = $animalCreateUseCase->apply(
            dto: $request->getDTO(),
        );

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_CREATED,
        );
    }

    public function getById(
        string $id,
        AnimalGetByIdUseCase $animalGetByIdUseCase
    ): JsonResponse {
        $animal = $animalGetByIdUseCase->apply(
            id: Uuid::fromString($id),
        );

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_OK,
        );
    }

    public function getBySlug(
        string $type,
        string $slug,
        AnimalGetBySlugUseCase $animalGetBySlugCase
    ): JsonResponse {
        $animal = $animalGetBySlugCase->apply(
            type: AnimalType::tryFrom($type),
            slug: StringValueObject::fromString($slug),
        );

        $animal->addSlug(SlugString::fromString($slug));

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_OK,
        );
    }

    public function update(
        AnimalUpdateRequest $request,
        string $id,
        AnimalUpdateUseCase $animalUpdateUseCase,
    ): JsonResponse {
        $animal = $animalUpdateUseCase->apply(
            id: Uuid::fromString($id),
            dto: $request->getDTO(),
        );

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function statusUpdate(
        string $id,
        AnimalStatusUpdateRequest $request,
        AnimalStatusUpdateUseCase $animalStatusUpdateUseCase
    ): JsonResponse {
        $animal = $animalStatusUpdateUseCase->apply(
            id: Uuid::fromString($id),
            status: AnimalStatus::tryFrom($request->input('status')),
        );

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function publish(
        string $id,
        AnimalPublishUseCase $animalPublishUseCase
    ): JsonResponse {
        $animal = $animalPublishUseCase->apply(Uuid::fromString($id));

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
            JsonResponse::HTTP_ACCEPTED,
        );
    }

    public function unpublish(
        string $id,
        AnimalUnpublishUseCase $animalUnpublishUseCase
    ): JsonResponse {
        $animal = $animalUnpublishUseCase->apply(Uuid::fromString($id));

        return response()->json(
            ['animal' => $this->animalMapper->toArray($animal)],
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

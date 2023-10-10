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
use Source\Application\Slug\UseCases\SlugCreateUseCase;
use Source\Application\Slug\UseCases\SlugGetBySluggableUseCase;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Slug;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Controllers\Controller;
use Source\Interface\Animal\Requests\AnimalIndexRequest;
use Source\Interface\Animal\Requests\AnimalStatusUpdateRequest;
use Source\Interface\Animal\Requests\AnimalStoreRequest;
use Source\Interface\Animal\Requests\AnimalUpdateRequest;
use Throwable;

final class AnimalController extends Controller
{
    public function index(
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $type = $request->validated('type')
            ? AnimalType::single($request->validated('type'))
            : null;
        $gender = $request->validated('gender')
            ? AnimalGender::tryFrom($request->validated('gender'))
            : null;
        $page = $request->validated('page');

        $result = $animalIndexUseCase->apply(
            $type,
            $gender,
            $page
        );

        $animals = array_map(
            function (Animal $animal) {
                $this->loadSlug($animal);
                return $animal->toArray();
            },
            $result['animals']
        );

        return response()->json([
            'animals' => $animals,
            'pagination' => $result['pagination']
        ]);
    }

    public function indexByType(
        string $type,
        AnimalIndexRequest $request,
        AnimalIndexUseCase $animalIndexUseCase,
    ): JsonResponse {
        $gender = $request->validated('gender')
            ? AnimalGender::tryFrom($request->validated('gender'))
            : null;
        $page = $request->validated('page');

        $result = $animalIndexUseCase->apply(
            AnimalType::single($type),
            $gender,
            $page
        );

        $animals = array_map(
            function (Animal $animal) {
                $this->loadSlug($animal);
                return $animal->toArray();
            },
            $result['animals']
        );

        return response()->json([
            'animals' => $animals,
            'pagination' => $result['pagination']
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(
        AnimalStoreRequest $request,
        AnimalCreateUseCase $animalCreateUseCase,
        SlugCreateUseCase $slugCreateUseCase
    ): JsonResponse {
        $animal = $animalCreateUseCase->apply(
            $request->validated()
        );

        $slugString = $animal->info()->name() . '-' .
            $animal->info()->type()->value . '-' .
            $animal->info()->gender()->value . '-' .
            $animal->info()->breed();

        $slug = $slugCreateUseCase->apply(
            slugString: $slugString,
            sluggableType: new AnimalModel(),
            sluggableId: $animal->id()
        );

        $animal->addSlug(Slug::fromString($slug->value()));

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_CREATED
        );
    }

    public function getById(
        string $id,
        AnimalGetByIdUseCase $animalGetByIdUseCase
    ): JsonResponse {
        $animal = $animalGetByIdUseCase->apply(
            Uuid::fromString($id)
        );

        $this->loadSlug($animal);

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_OK
        );
    }

    public function getBySlug(
        string $type,
        string $slug,
        AnimalGetBySlugUseCase $animalGetBySlugCase
    ): JsonResponse {
        $animal = $animalGetBySlugCase->apply(
            type: AnimalType::tryFrom($type),
            slug: $slug,
        );

        $animal->addSlug(Slug::fromString($slug));

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_OK
        );
    }

    public function update(
        AnimalUpdateRequest $request,
        string $id,
        AnimalUpdateUseCase $animalUpdateUseCase,
    ): JsonResponse {
        $animal = $animalUpdateUseCase->apply(
            id: Uuid::fromString($id),
            data: $request->validated(),
        );

        $this->loadSlug($animal);

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_ACCEPTED
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

        $this->loadSlug($animal);

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_ACCEPTED
        );
    }

    public function publish(
        string $id,
        AnimalPublishUseCase $animalPublishUseCase
    ): JsonResponse {
        $animal = $animalPublishUseCase->apply(
            Uuid::fromString($id)
        );

        $this->loadSlug($animal);

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_ACCEPTED
        );
    }

    public function unpublish(
        string $id,
        AnimalUnpublishUseCase $animalUnpublishUseCase
    ): JsonResponse {
        $animal = $animalUnpublishUseCase->apply(
            Uuid::fromString($id)
        );

        $this->loadSlug($animal);

        return response()->json(
            ['animal' => $animal->toArray()],
            JsonResponse::HTTP_ACCEPTED
        );
    }

    public function destroy(
        string $id,
        AnimalDestroyUseCase $animalDestroyUseCase
    ): JsonResponse {
        $animalDestroyUseCase->apply(
            Uuid::fromString($id)
        );

        return response()->json(
            [],
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    private function loadSlug(Animal $animal): void
    {
        $slugGetBySluggableUseCase = app(SlugGetBySluggableUseCase::class);
        $slug = $slugGetBySluggableUseCase->apply(
            sluggableType: new AnimalModel(),
            sluggableId: $animal->id()
        );

        $animal->addSlug(Slug::fromString($slug->value()));
    }
}

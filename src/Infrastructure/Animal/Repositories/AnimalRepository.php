<?php

namespace Source\Infrastructure\Animal\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Exceptions\AnimalNotFoundException;
use Source\Domain\Animal\Repositories\AnimalRepository as AnimalRepositoryContract;
use Source\Domain\Animal\Search\AnimalSearchCriteria;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Mappers\AnimalMapper;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Animal\QueryBuilders\AnimalQueryBuilder;
use Source\Infrastructure\MediaFile\Repositories\MediableRepository;

final class AnimalRepository implements AnimalRepositoryContract, MediableRepository
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected AnimalMapper $mapper,
    ) {
    }

    public function index(
        AnimalSearchCriteria $criteria,
        Pagination $pagination,
    ): array {
        /** @var AnimalModel[] $animals */
        $animals = $this->handleCriteria($criteria)
            ->offset($pagination->offset()->value)
            ->limit($pagination->limit->value)
            ->get()
            ->all();

        return array_map(
            fn (AnimalModel $model) => $this->mapper->modelToEntity($model),
            $animals,
        );
    }

    /**
     * @throws AnimalNotFoundException
     */
    public function getById(UuidInterface $id): Animal
    {
        /** @var ?AnimalModel $model */
        $model = AnimalModel::query()->find($id);

        if (!$model) {
            throw new AnimalNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    /**
     * @throws AnimalNotFoundException
     */
    public function getBySlug(
        AnimalType $type,
        StringValueObject $slug,
    ): Animal {
        /** @var ?AnimalModel $model */
        $model = AnimalModel::query()
            ->type($type)
            ->slug($slug)
            ->first();

        if (!$model) {
            throw new AnimalNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    public function create(Animal $animal): void
    {
        $this->connection
            ->transaction(function () use ($animal) {
                $model = $this->mapper->entityToModel($animal);

                $model->setAttribute('id', $animal->id);
                $model->setAttribute('created_at', CarbonImmutable::now());
                $model->setAttribute('updated_at', null);

                $model->save();
            });
    }

    public function update(
        UuidInterface $id,
        Animal $animal,
    ): void {
        /** @var AnimalModel $model */
        $model = AnimalModel::query()->find($id);

        $this->connection->transaction(function () use ($animal, $model) {
            $model = $this->mapper->entityToModel($animal, $model);

            $model->setAttribute('updated_at', CarbonImmutable::now());

            $model->save();
        });
    }

    public function delete(UuidInterface $id): void
    {
        AnimalModel::destroy([$id]);
    }

    public function exists(UuidInterface $id): bool
    {
        return AnimalModel::query()
            ->where('id', $id)
            ->exists();
    }

    public function totalCountByCriteria(AnimalSearchCriteria $criteria): TotalItems
    {
        $animalQueryBuilder = $this->handleCriteria($criteria);

        return TotalItems::fromInteger($animalQueryBuilder->count());
    }

    protected function handleCriteria(AnimalSearchCriteria $criteria): AnimalQueryBuilder
    {
        /** @var AnimalQueryBuilder $animalQueryBuilder */
        $animalQueryBuilder = AnimalModel::query();

        $animalQueryBuilder
            ->when(
                $criteria->name,
                fn (AnimalQueryBuilder $query, $name) => $query->name($name),
            )
            ->when(
                $criteria->type,
                fn (AnimalQueryBuilder $query, $type) => $query->type($type),
            )
            ->when(
                $criteria->gender,
                fn (AnimalQueryBuilder $query, $gender) => $query->gender($gender),
            )
            ->when(
                $criteria->ageMin,
                fn (AnimalQueryBuilder $query, $ageMin) => $query->ageMin($ageMin),
            )
            ->when(
                $criteria->ageMax,
                fn (AnimalQueryBuilder $query, $ageMax) => $query->ageMax($ageMax),
            );

        return $animalQueryBuilder;
    }
}

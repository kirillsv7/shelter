<?php

namespace Source\Infrastructure\Animal\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Exceptions\AnimalNotFoundException;
use Source\Domain\Animal\Repositories\AnimalRepository as AnimalRepositoryContract;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Mappers\AnimalMapper;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Animal\QueryBuilders\AnimalQueryBuilder;

final class AnimalRepository implements AnimalRepositoryContract
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
        $animals = $this->handleCriteria($criteria)
            ->offset($pagination->offset()->value)
            ->limit($pagination->limit->value)
            ->get()
            ->all();

        return array_map(
            /** @phpstan-ignore-next-line */
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

                $model->setAttribute('id', $animal->id());
                $model->setAttribute('created_at', CarbonImmutable::now());

                $model->save();
            });
    }

    public function update(UuidInterface $id, Animal $animal): void
    {
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

    public function totalCountByCriteria(AnimalSearchCriteria $criteria): int
    {
        $animalQueryBuilder = $this->handleCriteria($criteria);

        return $animalQueryBuilder->count();
    }

    protected function handleCriteria(AnimalSearchCriteria $criteria): AnimalQueryBuilder
    {
        $animalQueryBuilder = AnimalModel::query();

        if ($criteria->name) {
            $animalQueryBuilder = $animalQueryBuilder->name($criteria->name);
        }

        if ($criteria->type) {
            $animalQueryBuilder = $animalQueryBuilder->type($criteria->type);
        }

        if ($criteria->gender) {
            $animalQueryBuilder = $animalQueryBuilder->gender($criteria->gender);
        }

        if ($criteria->ageMin) {
            $animalQueryBuilder = $animalQueryBuilder->ageMin($criteria->ageMin);
        }

        if ($criteria->ageMax) {
            $animalQueryBuilder = $animalQueryBuilder->ageMax($criteria->ageMax);
        }

        return $animalQueryBuilder;
    }
}

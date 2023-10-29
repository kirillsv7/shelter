<?php

namespace Source\Infrastructure\Animal\Repositories;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Exceptions\AnimalNotFoundException;
use Source\Domain\Animal\Repositories\AnimalRepository as AnimalRepositoryContract;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\Model\Pagination;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Animal\QueryBuilders\AnimalQueryBuilder;

final class AnimalRepository implements AnimalRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection
    ) {
    }

    public function index(
        AnimalSearchCriteria $criteria,
        Pagination $pagination
    ): array {
        $animals = $this->handleCriteria($criteria)
            ->offset($pagination->offset()->value)
            ->limit($pagination->limit->value)
            ->get()
            ->all();

        return array_map(
            /** @phpstan-ignore-next-line */
            static fn (AnimalModel $model) => self::map($model),
            $animals
        );
    }

    public function getById(UuidInterface $id): ?Animal
    {
        /** @var ?AnimalModel $model */
        $model = AnimalModel::query()->find($id);

        if (!$model) {
            throw new AnimalNotFoundException('Animal doesn\'t exists');
        }

        return self::map($model);
    }

    public function getBySlug(AnimalType $type, string $slug): ?Animal
    {
        /** @var ?AnimalModel $model */
        $model = AnimalModel::query()
            ->type($type)
            ->slug($slug)
            ->first();

        if (!$model) {
            throw new AnimalNotFoundException('Animal doesn\'t exists');
        }

        return self::map($model);
    }

    public function create(Animal $animal): void
    {
        $model = new AnimalModel();

        $this->connection->transaction(function () use ($model, $animal) {
            $model->id = $animal->id();
            $model->name = $animal->info()->name();
            $model->type = $animal->info()->type()->value;
            $model->gender = $animal->info()->gender()->value;
            $model->breed = $animal->info()->breed();
            $model->birthdate = $animal->info()->birthdate();
            $model->entrydate = $animal->info()->entrydate();
            $model->status = $animal->status()->value;
            $model->published = $animal->published();
            $model->created_at = Carbon::now();

            $model->save();
        });
    }

    public function update(UuidInterface $id, Animal $animal): void
    {
        /** @var AnimalModel $model */
        $model = AnimalModel::query()->find($id);

        $this->connection->transaction(function () use ($model, $animal) {
            $model->name = $animal->info()->name();
            $model->type = $animal->info()->type()->value;
            $model->gender = $animal->info()->gender()->value;
            $model->breed = $animal->info()->breed();
            $model->birthdate = $animal->info()->birthdate();
            $model->entrydate = $animal->info()->entrydate();
            $model->status = $animal->status()->value;
            $model->published = $animal->published();
            $model->updated_at = Carbon::now();

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

    private function handleCriteria(AnimalSearchCriteria $criteria): AnimalQueryBuilder
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

    public static function map(AnimalModel $model): Animal
    {
        return Animal::make(
            id: Uuid::fromString($model->id),
            info: AnimalInfo::create(
                name: Name::fromString($model->name),
                type: AnimalType::tryFrom($model->type),
                gender: AnimalGender::tryFrom($model->gender),
                breed: Breed::fromString($model->breed),
                birthdate: new Carbon($model->birthdate),
                entrydate: new Carbon($model->entrydate)
            ),
            status: AnimalStatus::tryFrom($model->status),
            published: $model->published,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }
}

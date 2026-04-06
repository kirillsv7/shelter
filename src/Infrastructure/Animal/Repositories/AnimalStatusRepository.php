<?php

namespace Source\Infrastructure\Animal\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalStatusRepository as AnimalStatusRepositoryContract;
use Source\Infrastructure\Animal\Mappers\AnimalStatusUpdateMapper;
use Source\Infrastructure\Animal\Models\AnimalStatusModel;

final class AnimalStatusRepository implements AnimalStatusRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected AnimalStatusUpdateMapper $mapper,
    ) {
    }

    public function getByAnimalId(UuidInterface $id): array
    {
        $animalStatuses = AnimalStatusModel::query()
            ->where('animal_id', $id)
            ->get()
            ->all();

        return array_map(
            fn (AnimalStatusModel $model) => $this->mapper->modelToEntity($model),
            $animalStatuses,
        );
    }

    public function getByAnimalIds(array $ids): array
    {
        $animalStatuses = AnimalStatusModel::query()
            ->whereIn('animal_id', $ids)
            ->get()
            ->all();

        return array_map(
            fn (AnimalStatusModel $model) => $this->mapper->modelToEntity($model),
            $animalStatuses,
        );
    }

    public function create(AnimalStatus $animalStatus): void
    {
        $this->connection
            ->transaction(function () use ($animalStatus) {
                $model = $this->mapper->entityToModel($animalStatus);

                $model->setAttribute('id', $animalStatus->id);
                $model->setAttribute('created_at', CarbonImmutable::now());

                $model->save();
            });
    }
}

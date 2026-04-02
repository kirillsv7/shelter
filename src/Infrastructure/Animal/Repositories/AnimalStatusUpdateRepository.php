<?php

namespace Source\Infrastructure\Animal\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\Repositories\AnimalStatusUpdateRepository as AnimalStatusUpdateRepositoryContract;
use Source\Infrastructure\Animal\Mappers\AnimalStatusUpdateMapper;
use Source\Infrastructure\Animal\Models\AnimalStatusUpdateModel;

final class AnimalStatusUpdateRepository implements AnimalStatusUpdateRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected AnimalStatusUpdateMapper $mapper,
    ) {
    }

    public function getByAnimalId(UuidInterface $id): array
    {
        $animalStatusUpdates = AnimalStatusUpdateModel::query()
            ->where('animal_id', $id)
            ->get();

        return array_map(
            /** @phpstan-ignore-next-line */
            fn (AnimalStatusUpdateModel $model) => $this->mapper->modelToEntity($model),
            $animalStatusUpdates,
        );
    }

    public function create(AnimalStatusUpdate $animalStatusUpdate): void
    {
        $this->connection
            ->transaction(function () use ($animalStatusUpdate) {
                $model = $this->mapper->entityToModel($animalStatusUpdate);

                $model->setAttribute('id', $animalStatusUpdate->id);
                $model->setAttribute('created_at', CarbonImmutable::now());

                $model->save();
            });
    }
}

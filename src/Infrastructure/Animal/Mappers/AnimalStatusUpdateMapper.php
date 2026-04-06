<?php

namespace Source\Infrastructure\Animal\Mappers;

use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalStatus as AnimalStatusEnum;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalStatusModel;

final readonly class AnimalStatusUpdateMapper
{
    public function modelToEntity(AnimalStatusModel $model): AnimalStatus
    {
        return AnimalStatus::make(
            id: Uuid::fromString($model->getAttribute('id')),
            animalId: Uuid::fromString($model->getAttribute('animal_id')),
            status: AnimalStatusEnum::tryFrom($model->getAttribute('status')),
            notes: $model->getAttribute('notes')
                ? StringValueObject::fromString($model->getAttribute('notes'))
                : null,
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }

    public function entityToModel(
        AnimalStatus $animalStatus,
        ?AnimalStatusModel $model = null,
    ): AnimalStatusModel {
        if (null === $model) {
            $model = new AnimalStatusModel();
        }

        $model->setAttribute('animal_id', $animalStatus->animalId);
        $model->setAttribute('status', $animalStatus->status->value);
        $model->setAttribute('notes', $animalStatus->notes);

        return $model;
    }
}

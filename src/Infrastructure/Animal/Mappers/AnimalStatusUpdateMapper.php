<?php

namespace Source\Infrastructure\Animal\Mappers;

use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalStatusUpdateModel;

final readonly class AnimalStatusUpdateMapper
{
    public function modelToEntity(AnimalStatusUpdateModel $model): AnimalStatusUpdate
    {
        return AnimalStatusUpdate::make(
            id: Uuid::fromString($model->getAttribute('id')),
            animalId: Uuid::fromString($model->getAttribute('animal_id')),
            status: AnimalStatus::tryFrom($model->getAttribute('status')),
            notes: $model->getAttribute('notes')
                ? StringValueObject::fromString($model->getAttribute('notes'))
                : null,
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }

    public function entityToModel(
        AnimalStatusUpdate $animalStatusUpdate,
        ?AnimalStatusUpdateModel $model = null,
    ): AnimalStatusUpdateModel {
        if (null === $model) {
            $model = new AnimalStatusUpdateModel();
        }

        $model->setAttribute('animal_id', $animalStatusUpdate->animalId);
        $model->setAttribute('status', $animalStatusUpdate->status->value);
        $model->setAttribute('notes', $animalStatusUpdate->notes);

        return $model;
    }
}

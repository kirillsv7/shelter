<?php

namespace Source\Infrastructure\Animal\Mappers;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Infrastructure\Animal\Models\AnimalModel;

final readonly class AnimalMapper
{
    public function modelToEntity(AnimalModel $model): Animal
    {
        return Animal::make(
            id: Uuid::fromString($model->id),
            info: AnimalInfo::create(
                name: Name::fromString($model->getAttribute('name')),
                type: AnimalType::tryFrom($model->getAttribute('type')),
                gender: AnimalGender::tryFrom($model->getAttribute('gender')),
                breed: Breed::fromString($model->getAttribute('breed')),
                birthdate: new CarbonImmutable($model->getAttribute('birthdate')),
                entrydate: new CarbonImmutable($model->getAttribute('entrydate')),
            ),
            status: AnimalStatus::tryFrom($model->getAttribute('status')),
            published: $model->getAttribute('published'),
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }
}

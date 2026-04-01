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
            id: Uuid::fromString($model->getAttribute('id')),
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

    public function entityToModel(Animal $animal, ?AnimalModel $model = null): AnimalModel
    {
        if (null === $model) {
            $model = new AnimalModel();
        }

        $model->setAttribute('name', $animal->info()->name());
        $model->setAttribute('type', $animal->info()->type()->value);
        $model->setAttribute('gender', $animal->info()->gender()->value);
        $model->setAttribute('breed', $animal->info()->breed());
        $model->setAttribute('birthdate', $animal->info()->birthdate());
        $model->setAttribute('entrydate', $animal->info()->entrydate());
        $model->setAttribute('status', $animal->status()->value);
        $model->setAttribute('published', $animal->published());

        return $model;
    }
}

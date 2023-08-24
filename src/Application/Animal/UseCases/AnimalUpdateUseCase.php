<?php

namespace Source\Application\Animal\UseCases;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;

final class AnimalUpdateUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id,
        array $data
    ): Animal {
        $animal = $this->repository->getById($id);

        $animal->info()->change(
            array_key_exists('name', $data) ? Name::fromString($data['name']) : null,
            array_key_exists('type', $data) ? AnimalType::tryFrom($data['type']) : null,
            array_key_exists('gender', $data) ? AnimalGender::tryFrom($data['gender']) : null,
            array_key_exists('breed', $data) ? Breed::fromString($data['breed']) : null,
            array_key_exists('birthdate', $data) ? new Carbon($data['birthdate']) : null,
            array_key_exists('entrydate', $data) ? new Carbon($data['entrydate']) : null,
        );

        $this->repository->update(
            id: $id,
            animal: $animal
        );

        return $animal;
    }
}

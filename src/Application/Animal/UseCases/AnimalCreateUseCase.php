<?php

namespace Source\Application\Animal\UseCases;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Repositories\AnimalRepository;

final class AnimalCreateUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(array $data): Animal
    {
        $id = Uuid::uuid4();
        $info = AnimalInfo::fromArray($data);
        $createdAt = Carbon::now();

        $animal = Animal::create(
            id: $id,
            info: $info,
            createdAt: $createdAt
        );

        $this->repository->create($animal);

        return $animal;
    }
}

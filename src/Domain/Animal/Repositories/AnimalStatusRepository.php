<?php

namespace Source\Domain\Animal\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Throwable;

interface AnimalStatusRepository
{
    /**
     * @return AnimalStatus[]
     */
    public function getByAnimalId(UuidInterface $id): array;

    /**
     * @param  UuidInterface[]  $ids
     *
     * @return AnimalStatus[]
     */
    public function getByAnimalIds(array $ids): array;

    /**
     * @throws Throwable
     */
    public function create(AnimalStatus $animalStatus): void;
}

<?php

namespace Source\Domain\Animal\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Throwable;

interface AnimalStatusUpdateRepository
{
    /**
     * @return AnimalStatusUpdate[]
     */
    public function getByAnimalId(UuidInterface $id): array;

    /**
     * @throws Throwable
     */
    public function create(AnimalStatusUpdate $animalStatusUpdate): void;
}

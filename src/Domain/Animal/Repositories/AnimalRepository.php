<?php

namespace Source\Domain\Animal\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Exceptions\AnimalNotFoundException;
use Source\Domain\Shared\Model\Pagination;

interface AnimalRepository
{
    public function index(
        AnimalSearchCriteria $criteria,
        Pagination $pagination
    ): array;

    /**
     * @throws AnimalNotFoundException
     */
    public function getById(UuidInterface $id): ?Animal;

    /**
     * @throws AnimalNotFoundException
     */
    public function getBySlug(AnimalType $type, string $slug): ?Animal;

    /**
     * @throws \Throwable
     */
    public function create(Animal $animal): void;

    /**
     * @throws \Throwable
     */
    public function update(UuidInterface $id, Animal $animal): void;

    public function delete(UuidInterface $id): void;

    public function criteriaTotalCount(AnimalSearchCriteria $criteria): int;
}

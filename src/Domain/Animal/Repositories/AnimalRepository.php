<?php

namespace Source\Domain\Animal\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Exceptions\AnimalNotFoundException;
use Source\Domain\Animal\Search\AnimalSearchCriteria;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Throwable;

interface AnimalRepository
{
    /**
     * @return Animal[]
     */
    public function index(
        AnimalSearchCriteria $criteria,
        Pagination $pagination,
    ): array;

    /**
     * @throws AnimalNotFoundException
     */
    public function getById(UuidInterface $id): Animal;

    /**
     * @throws AnimalNotFoundException
     */
    public function getBySlug(
        AnimalType $type,
        StringValueObject $slug,
    ): Animal;

    /**
     * @throws Throwable
     */
    public function create(Animal $animal): void;

    /**
     * @throws Throwable
     */
    public function update(
        UuidInterface $id,
        Animal $animal,
    ): void;

    public function delete(UuidInterface $id): void;

    public function totalCountByCriteria(AnimalSearchCriteria $criteria): TotalItems;
}

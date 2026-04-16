<?php

namespace Source\Domain\Organization\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\Exceptions\OrganizationNotFoundException;
use Source\Domain\Organization\Search\OrganizationSearchCriteria;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Throwable;

interface OrganizationRepository
{
    /**
     * @return Organization[]
     */
    public function index(
        OrganizationSearchCriteria $criteria,
        Pagination $pagination,
    ): array;

    /**
     * @throws OrganizationNotFoundException
     */
    public function getById(UuidInterface $id): Organization;

    /**
     * @throws OrganizationNotFoundException
     */
    public function getBySlug(StringValueObject $slug): Organization;

    /**
     * @throws Throwable
     */
    public function create(Organization $organization): void;

    /**
     * @throws Throwable
     */
    public function update(
        UuidInterface $id,
        Organization $organization,
    ): void;

    public function delete(UuidInterface $id): void;

    public function totalCountByCriteria(OrganizationSearchCriteria $criteria): TotalItems;
}

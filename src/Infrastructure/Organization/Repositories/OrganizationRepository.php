<?php

namespace Source\Infrastructure\Organization\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\Exceptions\OrganizationNotFoundException;
use Source\Domain\Organization\Repositories\OrganizationRepository as OrganizationRepositoryContract;
use Source\Domain\Organization\Search\OrganizationSearchCriteria;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\Model\PaginationValueObjects\TotalItems;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\MediaFile\Repositories\MediableRepository;
use Source\Infrastructure\Organization\Mappers\OrganizationMapper;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Infrastructure\Organization\QueryBuilders\OrganizationQueryBuilder;

final class OrganizationRepository implements OrganizationRepositoryContract, MediableRepository
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected OrganizationMapper $mapper,
    ) {
    }

    public function index(
        OrganizationSearchCriteria $criteria,
        Pagination $pagination,
    ): array {
        /** @var OrganizationModel[] $organizations */
        $organizations = $this->handleCriteria($criteria)
            ->offset($pagination->offset()->value)
            ->limit($pagination->limit->value)
            ->get()
            ->all();

        return array_map(
            fn (OrganizationModel $model) => $this->mapper->modelToEntity($model),
            $organizations,
        );
    }

    /**
     * @throws OrganizationNotFoundException
     */
    public function getById(UuidInterface $id): Organization
    {
        /** @var ?OrganizationModel $model */
        $model = OrganizationModel::query()->find($id);

        if (!$model) {
            throw new OrganizationNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    /**
     * @throws OrganizationNotFoundException
     */
    public function getBySlug(
        StringValueObject $slug,
    ): Organization {
        /** @var ?OrganizationModel $model */
        $model = OrganizationModel::query()
            ->slug($slug)
            ->first();

        if (!$model) {
            throw new OrganizationNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    public function create(Organization $organization): void
    {
        $this->connection
            ->transaction(function () use ($organization) {
                $model = $this->mapper->entityToModel($organization);

                $model->setAttribute('id', $organization->id);
                $model->setAttribute('created_at', CarbonImmutable::now());
                $model->setAttribute('updated_at', null);

                $model->save();
            });
    }

    public function update(
        UuidInterface $id,
        Organization $organization,
    ): void {
        /** @var OrganizationModel $model */
        $model = OrganizationModel::query()->find($id);

        $this->connection->transaction(function () use ($organization, $model) {
            $model = $this->mapper->entityToModel($organization, $model);

            $model->setAttribute('updated_at', CarbonImmutable::now());

            $model->save();
        });
    }

    public function delete(UuidInterface $id): void
    {
        OrganizationModel::destroy([$id]);
    }

    public function exists(UuidInterface $id): bool
    {
        return OrganizationModel::query()
            ->where('id', $id)
            ->exists();
    }

    public function totalCountByCriteria(OrganizationSearchCriteria $criteria): TotalItems
    {
        $organizationQueryBuilder = $this->handleCriteria($criteria);

        return TotalItems::fromInteger($organizationQueryBuilder->count());
    }

    protected function handleCriteria(OrganizationSearchCriteria $criteria): OrganizationQueryBuilder
    {
        /** @var OrganizationQueryBuilder $organizationQueryBuilder */
        $organizationQueryBuilder = OrganizationModel::query();

        $organizationQueryBuilder
            ->when(
                $criteria->text,
                fn (OrganizationQueryBuilder $query, $text) => $query->name($text),
            )
            ->when(
                $criteria->isVerified,
                fn (OrganizationQueryBuilder $query, $isVerified) => $query->verified($isVerified),
            )
            ->when(
                $criteria->isActive,
                fn (OrganizationQueryBuilder $query, $isActive) => $query->active($isActive),
            );

        return $organizationQueryBuilder;
    }
}

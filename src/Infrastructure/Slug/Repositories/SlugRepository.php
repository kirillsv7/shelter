<?php

namespace Source\Infrastructure\Slug\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Exceptions\SlugNotFoundException;
use Source\Domain\Slug\Repositories\SlugRepository as SlugRepositoryContract;
use Source\Infrastructure\Slug\Mappers\SlugMapper;
use Source\Infrastructure\Slug\Models\SlugModel;

final class SlugRepository implements SlugRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected SlugMapper $mapper,
    ) {
    }

    public function create(Slug $slug): void
    {
        $this->connection->transaction(function () use ($slug) {
            $model = $this->mapper->entityToModel($slug);

            $model->setAttribute('id', $slug->id);
            $model->setAttribute('created_at', CarbonImmutable::now());

            $model->save();
        });
    }

    public function getBySluggable(
        StringValueObject $sluggableType,
        UuidInterface $sluggableId
    ): Slug {
        $model = SlugModel::query()
            ->where('sluggable_type', $sluggableType)
            ->where('sluggable_id', $sluggableId)
            ->first();

        if (!$model) {
            throw new SlugNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    public function getBySluggableUuid(UuidInterface $id): Slug
    {
        $model = SlugModel::query()
            ->where('sluggable_id', $id)
            ->first();

        if (!$model) {
            throw new SlugNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    public function getBySluggableUuids(array $ids): array
    {
        return SlugModel::query()
            ->whereIn('sluggable_id', $ids)
            ->get()
            ->map(fn (SlugModel $model) => $this->mapper->modelToEntity($model))
            ->toArray();
    }

    public function update(Slug $slug): void
    {
        $model = SlugModel::query()->find($slug->id);

        $this->connection->transaction(function () use ($slug, $model) {
            $model = $this->mapper->entityToModel($slug, $model);

            $model->setAttribute('updated_at', CarbonImmutable::now());

            $model->save();

            $model->save();
        });
    }
}

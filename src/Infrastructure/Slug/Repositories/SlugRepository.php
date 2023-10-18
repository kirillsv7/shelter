<?php

namespace Source\Infrastructure\Slug\Repositories;

use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Exceptions\SlugNotFoundException;
use Source\Domain\Slug\Repositories\SlugRepository as SlugRepositoryContract;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Laravel\Models\BaseModel;
use Source\Infrastructure\Slug\Models\SlugModel;

final class SlugRepository implements SlugRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection
    ) {
    }

    public function create(Slug $slug): int
    {
        $model = new SlugModel();

        $this->connection->transaction(function () use ($model, $slug) {
            $model->slug = $slug->value();
            $model->sluggable_type = $slug->sluggableType();
            $model->sluggable_id = $slug->sluggableId();

            $model->save();
        });

        return $model->id;
    }

    public function getBySluggable(
        BaseModel $sluggableType,
        UuidInterface $sluggableId
    ): ?Slug {
        $model = SlugModel::query()
            ->where('sluggable_type', get_class($sluggableType))
            ->where('sluggable_id', $sluggableId)
            ->first();

        if (!$model) {
            throw new SlugNotFoundException('Slug doesn\'t exists');
        }

        return $this->map($model);
    }

    public function getBySluggableUuid(UuidInterface $id): ?Slug
    {
        $model = SlugModel::query()
            ->where('sluggable_id', $id)
            ->first();

        if (!$model) {
            throw new SlugNotFoundException('Slug doesn\'t exists');
        }

        return $this->map($model);
    }

    public function update(Slug $slug): void
    {
        $model = SlugModel::query()->find($slug->id());

        $this->connection->transaction(function () use ($model, $slug) {
            $model->slug = $slug->value();

            $model->save();
        });
    }

    private function map(SlugModel $model): Slug
    {
        return Slug::create(
            id: $model->id,
            value: SlugString::fromString($model->slug),
            sluggableType: new $model->sluggable_type(),
            sluggableId: Uuid::fromString($model->sluggable_id)
        );
    }
}

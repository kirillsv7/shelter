<?php

namespace Source\Infrastructure\Slug\Mappers;

use Ramsey\Uuid\Uuid;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Slug\Models\SlugModel;

final readonly class SlugMapper
{
    public function modelToEntity(SlugModel $model): Slug
    {
        return Slug::make(
            id: Uuid::fromString($model->getAttribute('id')),
            value: SlugString::fromString($model->getAttribute('slug')),
            sluggableType: StringValueObject::fromString($model->getAttribute('sluggable_type')),
            sluggableId: Uuid::fromString($model->getAttribute('sluggable_id')),
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }

    public function entityToModel(
        Slug $slug,
        ?SlugModel $model = null,
    ): SlugModel {
        if (null === $model) {
            $model = new SlugModel();
        }

        $model->setAttribute('slug', $slug->value());
        $model->setAttribute('sluggable_type', $slug->sluggableType);
        $model->setAttribute('sluggable_id', $slug->sluggableId);

        return $model;
    }
}

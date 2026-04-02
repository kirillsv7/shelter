<?php

namespace Source\Application\Animal\UseCases\Traits;

use Source\Domain\Shared\AggregateWithSlug;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;

trait LoadSlugTrait
{
    protected function loadSlug(AggregateWithSlug $aggregateWithSlug, StringValueObject $sluggableType): void
    {
        $slugRepository = app(SlugRepository::class);

        $slug = $slugRepository->getBySluggable(
            sluggableType: $sluggableType,
            sluggableId: $aggregateWithSlug->id(),
        );

        $aggregateWithSlug->addSlug(SlugString::fromString($slug->value()));
    }
}

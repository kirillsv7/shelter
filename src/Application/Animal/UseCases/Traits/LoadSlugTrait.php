<?php

namespace Source\Application\Animal\UseCases\Traits;

use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Animal\Models\AnimalModel;

trait LoadSlugTrait
{
    protected function loadSlug(Animal $animal): void
    {
        $slugRepository = app(SlugRepository::class);

        $slug = $slugRepository->getBySluggable(
            sluggableType: new AnimalModel(),
            sluggableId: $animal->id(),
        );

        $animal->addSlug(SlugString::fromString($slug->value()));
    }
}

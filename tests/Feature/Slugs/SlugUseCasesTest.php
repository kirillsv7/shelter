<?php

namespace Tests\Feature\Slugs;

use Ramsey\Uuid\Uuid;
use Source\Application\Slug\UseCases\SlugGetBySluggableUseCase;
use Source\Domain\Slug\Exceptions\SlugNotFoundException;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;
use Tests\FeatureTestCase;

class SlugUseCasesTest extends FeatureTestCase
{
    public function testGetBySluggable(): void
    {
        $animal = AnimalModel::factory()->create();

        $slugGetBySluggableUseCase = app(SlugGetBySluggableUseCase::class);

        $slug = $slugGetBySluggableUseCase->apply(
            $animal->getModel(),
            $animal->id
        );

        $this->assertEquals(get_class($animal->getModel()), $slug->sluggableType());
    }

    public function testGetBySluggableNotFound(): void
    {
        $this->expectException(SlugNotFoundException::class);

        AnimalModel::factory()->create();

        $slugGetBySluggableUseCase = app(SlugGetBySluggableUseCase::class);

        $slugGetBySluggableUseCase->apply(
            new MediaFileModel(),
            Uuid::fromString(fake()->uuid())
        );
    }
}

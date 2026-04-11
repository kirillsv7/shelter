<?php

namespace Tests\Feature\Slug;

use Ramsey\Uuid\Uuid;
use Source\Application\Slug\UseCases\SlugGetBySluggableUseCase;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Exceptions\SlugNotFoundException;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Tests\FeatureTestCase;

class SlugUseCasesTest extends FeatureTestCase
{
    public function testGetBySluggable(): void
    {
        $animal = AnimalModel::factory()->create();

        $slugGetBySluggableUseCase = app(SlugGetBySluggableUseCase::class);

        $slugResponseDTO = $slugGetBySluggableUseCase->apply(
            StringValueObject::fromString(AnimalModel::class),
            $animal->id,
        );

        $slugData = $slugResponseDTO->jsonSerialize()->jsonSerialize();

        $this->assertEquals(
            StringValueObject::fromString(AnimalModel::class),
            $slugData['sluggable_type'],
        );
    }

    public function testGetBySluggableNotFound(): void
    {
        $this->expectException(SlugNotFoundException::class);

        AnimalModel::factory()->create();

        $slugGetBySluggableUseCase = app(SlugGetBySluggableUseCase::class);

        $slugGetBySluggableUseCase->apply(
            StringValueObject::fromString(AnimalModel::class),
            Uuid::fromString(fake()->uuid()),
        );
    }
}

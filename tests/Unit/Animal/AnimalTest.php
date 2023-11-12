<?php

namespace Tests\Unit\Animal;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalDeleted;
use Source\Domain\Animal\Events\AnimalPublished;
use Source\Domain\Animal\Events\AnimalStatusChanged;
use Source\Domain\Animal\Events\AnimalUnpublished;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Animal\ValueObjects\Slug;
use Tests\UnitTestCase;

class AnimalTest extends UnitTestCase
{
    public function testAnimalCreate()
    {
        $animal = $this->animalCreate();

        $this->assertEventsHas(AnimalCreated::class, $animal->releaseEvents());
    }

    public function testAnimalStatusChange()
    {
        $animal = $this->animalCreate();

        $animalStatus = AnimalStatus::Adoption;

        $animal->changeStatus($animalStatus);

        $this->assertEventsHas(AnimalStatusChanged::class, $animal->releaseEvents());

        $this->assertEquals($animal->status(), $animalStatus);
    }

    public function testAnimalStatusDoesntChange()
    {
        $animal = $this->animalCreate();

        $animalStatus = AnimalStatus::Adoption;
        $animal->changeStatus($animalStatus);
        $animal->releaseEvents();
        $animal->changeStatus($animalStatus);

        $this->assertEventsHasNot(AnimalStatusChanged::class, $animal->releaseEvents());

        $this->assertEquals($animal->status(), $animalStatus);
    }

    public function testAnimalPublish()
    {
        $animal = $this->animalCreate();

        $animal->publish();

        $this->assertEventsHas(AnimalPublished::class, $animal->releaseEvents());

        $this->assertTrue($animal->published());
    }

    public function testAnimalAlreadyPublished()
    {
        $animal = $this->animalCreate();

        $animal->publish();
        $animal->releaseEvents();

        $animal->publish();

        $this->assertEventsHasNot(AnimalPublished::class, $animal->releaseEvents());

        $this->assertTrue($animal->published());
    }

    public function testAnimalUnpublish()
    {
        $animal = $this->animalCreate();

        $animal->publish();
        $animal->releaseEvents();
        $animal->unpublish();

        $this->assertEventsHas(AnimalUnpublished::class, $animal->releaseEvents());

        $this->assertTrue(!$animal->published());
    }

    public function testAnimalAlreadyUnpublished()
    {
        $animal = $this->animalCreate();

        $animal->unpublish();

        $this->assertEventsHasNot(AnimalUnpublished::class, $animal->releaseEvents());

        $this->assertTrue(!$animal->published());
    }

    public function testAnimalDelete()
    {
        $animal = $this->animalCreate();

        $animal->delete();

        $this->assertEventsHas(AnimalDeleted::class, $animal->releaseEvents());
    }

    public function testAnimalAddSlug()
    {
        $animal = $this->animalCreate();

        $slug = Slug::fromString('animal-slug');

        $animal->addSlug($slug);

        $this->assertEquals($animal->slug(), $slug);
    }

    public function testAnimalConvertToArray()
    {
        $animal = $this->animalCreate();

        $animalArray = $animal->toArray();

        $this->assertIsArray($animalArray);

        $this->assertEquals($animalArray, [
            'id' => $animal->id(),
            'info' => $animal->info()->toArray(),
            'age' => $animal->age()->value,
            'status' => $animal->status(),
            'published' => $animal->published(),
            'created_at' => $animal->createdAt(),
            'updated_at' => $animal->updatedAt(),
            'slug' => $animal->slug(),
        ]);
    }

    private function animalCreate(): Animal
    {
        return Animal::create(
            id: Uuid::uuid4(),
            info: AnimalInfo::create(
                name: Name::fromString(fake()->firstName()),
                type: fake()->randomElement(AnimalType::cases()),
                gender: fake()->randomElement(AnimalGender::cases()),
                breed: Breed::fromString(fake()->word()),
                birthdate: Carbon::today()->subDays(rand(30, 365 * 5)),
                entrydate: Carbon::today(),
            ),
            createdAt: Carbon::now()
        );
    }
}

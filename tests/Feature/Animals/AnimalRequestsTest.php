<?php

namespace Tests\Feature\Animals;

use Illuminate\Support\Carbon;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Tests\FeatureTestCase;

class AnimalRequestsTest extends FeatureTestCase
{
    public function testAnimalIndex()
    {
        AnimalModel::factory(110)->create();

        $responsePage1 = $this->getJson(route('animal.index'));
        $responsePage2 = $this->getJson(route('animal.index', ['page' => 2]));
        $responsePage6 = $this->getJson(route('animal.index', ['page' => 6]));

        $responsePage1
            ->assertOk()
            ->assertJsonCount(20, 'animals');

        $responsePage2
            ->assertOk()
            ->assertJsonCount(20, 'animals');

        $responsePage6
            ->assertOk()
            ->assertJsonCount(10, 'animals');
    }

    public function testAnimalIndexWithParams()
    {
        $name = 'Dobby';

        AnimalModel::factory(50)->create();
        AnimalModel::factory()->create([
            'name' => $name
        ]);
        AnimalModel::factory(50)->create();

        $responseNameDobby = $this->getJson(route('animal.index', ['name' => $name]));
        $responseTypeDogs = $this->getJson(route('animal.index', ['type' => 'dogs']));
        $responseGenderMale = $this->getJson(route('animal.index', ['gender' => 'male']));
        $responseTypeCatsAndGenderFemale = $this->getJson(
            route('animal.index', [
                'type' => 'cats',
                'gender' => 'female'
            ])
        );

        $responseNameDobby
            ->assertOk()
            ->assertJsonFragment(['name' => $name]);

        $responseTypeDogs
            ->assertOk()
            ->assertJsonFragment(['type' => 'dog'])
            ->assertJsonMissing(['type' => 'cat']);

        $responseGenderMale
            ->assertOk()
            ->assertJsonFragment(['gender' => 'male'])
            ->assertJsonMissing(['gender' => 'female']);

        $responseTypeCatsAndGenderFemale
            ->assertOk()
            ->assertJsonFragment(['type' => 'cat'])
            ->assertJsonFragment(['gender' => 'female'])
            ->assertJsonMissing(['type' => 'dog'])
            ->assertJsonMissing(['gender' => 'male']);
    }

    public function testAnimalIndexByType()
    {
        AnimalModel::factory(110)->create([
            'type' => AnimalType::Dog->value
        ]);

        $responsePage1 = $this->getJson(
            route('animal.index-by-type', [
                'animals' => 'dogs'
            ])
        );
        $responsePage2 = $this->getJson(
            route('animal.index-by-type', [
                'animals' => 'dogs',
                'page' => 2
            ])
        );
        $responsePage6 = $this->getJson(
            route('animal.index-by-type', [
                'animals' => 'dogs',
                'page' => 6
            ])
        );

        $responsePage1
            ->assertOk()
            ->assertJsonCount(20, 'animals')
            ->assertJsonFragment(['type' => 'dog'])
            ->assertJsonMissing(['type' => 'cat']);

        $responsePage2
            ->assertOk()
            ->assertJsonCount(20, 'animals')
            ->assertJsonFragment(['type' => 'dog'])
            ->assertJsonMissing(['type' => 'cat']);
        $responsePage6
            ->assertOk()
            ->assertJsonCount(10, 'animals')
            ->assertJsonFragment(['type' => 'dog'])
            ->assertJsonMissing(['type' => 'cat']);
    }

    public function testAnimalStore()
    {
        $animal = $this->generateAnimalDataForRequest();

        $response = $this->postJson(
            route('animal.store'),
            $animal
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'name' => $animal['name'],
                'type' => $animal['type'],
                'gender' => $animal['gender'],
                'breed' => $animal['breed'],
                'birthdate' => $animal['birthdate'],
                'entrydate' => $animal['entrydate'],
            ]);
    }

    public function testAnimalStoreValidation()
    {
        $response = $this->postJson(
            route('animal.store'),
            []
        );

        $response
            ->assertUnprocessable()
            ->assertInvalid([
                'name',
                'type',
                'gender',
                'breed',
                'birthdate',
                'entrydate',
            ]);
    }

    public function testAnimalGetById()
    {
        $animal = AnimalModel::factory()->create();

        $response = $this->getJson(
            route('animal.get-by-id', ['id' => $animal->id])
        );

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $animal->id,
                'info' => [
                    'name' => $animal->name,
                    'type' => $animal->type,
                    'gender' => $animal->gender,
                    'breed' => $animal->breed,
                    'birthdate' => $animal->birthdate,
                    'entrydate' => $animal->entrydate,
                ],
                'status' => $animal->status,
                'published' => $animal->published,
                'slug' => $animal->slug->slug,
            ]);
    }

    public function testAnimalGetBySlug()
    {
        $animal = AnimalModel::factory()->create();

        $response = $this->getJson(
            route('animal.get-by-slug', [
                'animal' => $animal->type,
                'slug' => $animal->slug->slug,
            ])
        );

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $animal->id,
                'info' => [
                    'name' => $animal->name,
                    'type' => $animal->type,
                    'gender' => $animal->gender,
                    'breed' => $animal->breed,
                    'birthdate' => $animal->birthdate,
                    'entrydate' => $animal->entrydate,
                ],
                'status' => $animal->status,
                'published' => $animal->published,
                'slug' => $animal->slug->slug,
            ]);
    }

    public function testAnimalUpdate()
    {
        $animal = AnimalModel::factory()->create();

        $animalNewData = $this->generateAnimalDataForRequest();

        $response = $this->putJson(
            route('animal.update', ['id' => $animal->id]),
            $animalNewData
        );

        $response
            ->assertAccepted()
            ->assertJsonFragment([
                'id' => $animal->id,
                'info' => $animalNewData,
                'status' => $animal->status,
                'published' => $animal->published,
                'slug' => $animal->slug->slug,
            ]);
    }

    public function testAnimalStatusChange()
    {
        $animal = AnimalModel::factory()
            ->create([
                'status' => AnimalStatus::Adoption->value,
            ]);

        $animalNewStatus = ['status' => AnimalStatus::Adopted->value];

        $response = $this->putJson(
            route('animal.status-update', ['id' => $animal->id]),
            $animalNewStatus
        );

        $response
            ->assertAccepted()
            ->assertJsonFragment($animalNewStatus);
    }

    public function testAnimalPublish()
    {
        $animal = AnimalModel::factory()->create([
            'published' => false,
        ]);

        $response = $this->post(route('animal.publish', ['id' => $animal->id]));

        $response
            ->assertAccepted()
            ->assertJsonFragment([
                'published' => true,
            ]);
    }

    public function testAnimalUnpublish()
    {
        $animal = AnimalModel::factory()->create([
            'published' => true,
        ]);

        $response = $this->post(route('animal.unpublish', ['id' => $animal->id]));

        $response
            ->assertAccepted()
            ->assertJsonFragment([
                'published' => false,
            ]);
    }

    public function testAnimalDestroy()
    {
        $animal = AnimalModel::factory()->create();

        $response = $this->delete(route('animal.destroy', ['id' => $animal->id]));

        $response->assertNoContent();
    }

    private function generateAnimalDataForRequest(): array
    {
        return [
            'name' => fake()->firstName(),
            'type' => fake()->randomElement(AnimalType::cases())->value,
            'gender' => fake()->randomElement(AnimalGender::cases())->value,
            'breed' => fake()->text(20),
            'birthdate' => Carbon::today()->subDays(rand(30, 365 * 5))->format('Y-m-d'),
            'entrydate' => Carbon::today()->format('Y-m-d'),
        ];
    }
}

<?php

namespace Source\Infrastructure\Animal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Infrastructure\Slug\Models\SlugModel;

final class AnimalFactory extends Factory
{
    protected $model = AnimalModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid7(),
            'name' => fake()->firstName(),
            'type' => fake()->randomElement(AnimalType::cases())->value,
            'gender' => fake()->randomElement(AnimalGender::cases())->value,
            'breed' => fake()->text(20),
            'birthdate' => Carbon::today()->subDays(rand(30, 365 * 5))->format(config('app.date_format')),
            'entrydate' => Carbon::today()->format(config('app.date_format')),
            'status' => fake()->randomElement(AnimalStatus::cases())->value,
            'is_published' => fake()->boolean(),
        ];
    }

    public function configure(): AnimalFactory
    {
        return $this
            ->afterCreating(function (Model $model) {
                /** @var AnimalModel $animal */
                $animal = $model;

                $randomOrganization = OrganizationModel::all()->random();

                $animal->organizations()
                    ->attach($randomOrganization->getAttribute('id'));

                $slug = new SlugModel();

                $slugString = implode(DIRECTORY_SEPARATOR, [
                    $animal->getAttribute('name'),
                    $animal->getAttribute('type'),
                    $animal->getAttribute('gender'),
                    $animal->getAttribute('breed'),
                ]);

                $slug->setAttribute('slug', Str::slug($slugString));
                $slug->setAttribute('sluggable_type', get_class($animal));
                $slug->setAttribute('sluggable_id', $animal->getAttribute('id'));

                $slug->save();
            });
    }
}

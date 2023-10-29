<?php

namespace Source\Infrastructure\Animal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Infrastructure\Animal\Models\AnimalModel;
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
            'id' => Uuid::uuid4(),
            'name' => fake()->firstName(),
            'type' => fake()->randomElement(AnimalType::cases())->value,
            'gender' => fake()->randomElement(AnimalGender::cases())->value,
            'breed' => fake()->text(20),
            'birthdate' => Carbon::today()->subDays(rand(30, 365 * 5))->format('Y-m-d'),
            'entrydate' => Carbon::today()->format('Y-m-d'),
            'status' => fake()->randomElement(AnimalStatus::cases())->value,
            'published' => fake()->boolean(),
        ];
    }

    public function configure()
    {
        /** @phpstan-ignore-next-line */
        return $this->afterCreating(function (AnimalModel $animal) {
            $slug = new SlugModel();

            $slug->slug = Str::slug($animal->name.'-'.$animal->type.'-'.$animal->gender.'-'.$animal->breed);
            $slug->sluggable_type = get_class($animal);
            $slug->sluggable_id = $animal->id;

            $slug->save();
        });
    }
}

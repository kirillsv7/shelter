<?php

namespace Source\Infrastructure\Organization\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Infrastructure\Slug\Models\SlugModel;

/**
 * @extends Factory<OrganizationModel>
 */
class OrganizationFactory extends Factory
{
    protected $model = OrganizationModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid7(),
            'name' => fake()->company(),
            'address' => [
                'addressLine1' => fake()->address(),
                'addressLine2' => fake()->address(),
                'city' => fake()->city(),
                'state' => fake()->word(),
                'postcode' => fake()->postcode(),
                'country' => fake()->country(),
            ],
            'contacts' => [
                'email' => fake()->email(),
                'phone' => fake()->phoneNumber(),
                'website' => fake()->url(),
            ],
            'socials' => [
                'facebook' => fake()->url(),
                'instagram' => fake()->url(),
            ],
            'is_verified' => fake()->boolean(),
            'is_active' => fake()->boolean(),
        ];
    }

    public function configure(): OrganizationFactory
    {
        return $this
            ->afterCreating(function (Model $model) {
                /** @var OrganizationModel $organization */
                $organization = $model;

                $slug = new SlugModel();

                $slugString = implode(DIRECTORY_SEPARATOR, [
                    $organization->getAttribute('name'),
                ]);

                $slug->setAttribute('slug', Str::slug($slugString));
                $slug->setAttribute('sluggable_type', get_class($organization));
                $slug->setAttribute('sluggable_id', $organization->getAttribute('id'));

                $slug->save();
            });
    }
}

<?php

namespace Tests\Feature\Slug;

use Source\Infrastructure\Animal\Models\AnimalModel;
use Tests\FeatureTestCase;

class SlugsRequestsTest extends FeatureTestCase
{
    public function testSlugUpdate()
    {
        $animal = AnimalModel::factory()->create();

        $newSlug = 'this-is-test-slug';

        $response = $this->post(route('slug.update', ['id' => $animal->id]), [
            'slug' => $newSlug
        ]);

        $response
            ->assertAccepted()
            ->assertJsonFragment([
                'slug' => $newSlug
            ]);
    }

    public function testSlugUpdateNotFoundSlug()
    {
        AnimalModel::factory()->create();

        $newSlug = 'this-is-test-slug';

        $response = $this->post(route('slug.update', ['id' => fake()->uuid()]), [
            'slug' => $newSlug
        ]);

        $response->assertNotFound();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Source\Infrastructure\Animal\Models\AnimalModel;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AnimalModel::factory(50)->create();
    }
}

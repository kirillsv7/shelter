<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Source\Infrastructure\Organization\Models\OrganizationModel;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationModel::factory(10)->create();
    }
}

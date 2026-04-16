<?php

namespace Tests\Feature\MediaFile;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Enums\MediableFolder;
use Source\Infrastructure\MediaFile\Enums\MediableModel;
use Source\Infrastructure\MediaFile\Services\PublicStorageMediaFileRouteGenerator;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Tests\FeatureTestCase;

class MediaFilesRequestsTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        OrganizationModel::factory(3)->create();
    }

    public function testMediaFileUpload(): void
    {
        $disk = 'public';

        Storage::fake($disk);

        $model = 'Animal';

        $animal = AnimalModel::factory()->create();

        $image = UploadedFile::fake()->image($animal->name . '.jpg');

        $fileName = 'original.' . $image->extension();

        $mediaFileRouteGenerator = $this->app->make(PublicStorageMediaFileRouteGenerator::class);

        $fileRoute = $mediaFileRouteGenerator(
            MediableFolder::fromName(MediableModel::Animal->name),
            $animal->id,
            $image,
        );

        $response = $this->post(
            route('media-file.store'),
            [
                'model' => $model,
                'id' => (string)$animal->id,
                'file' => $image,
            ],
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'storageInfo' => [
                    'disk' => $disk,
                    'route' => $fileRoute,
                    'fileName' => $fileName,
                ],
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Services\PublicStorageMediaFileRouteGenerator;
use Tests\FeatureTestCase;

class MediaFilesRequestsTest extends FeatureTestCase
{
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
            $animal,
            $animal->id,
            $image
        );

        $response = $this->post(
            route('media-file.store'),
            [
                'model' => $model,
                'id' => (string)$animal->id,
                'file' => $image
            ]
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'storage_info' => [
                    'disk' => $disk,
                    'route' => $fileRoute,
                    'fileName' => $fileName
                ],
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

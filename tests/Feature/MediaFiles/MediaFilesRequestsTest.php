<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Services\PublicStorageMediaFilePathGenerator;
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

        $mediaFilePathGenerator = $this->app->make(PublicStorageMediaFilePathGenerator::class);

        $filePath = $mediaFilePathGenerator(
            $animal,
            $animal->id,
            $image
        );

        $response = $this->post(
            route('mediafile.store'),
            [
                'model' => $model,
                'id' => (string)$animal->id,
                'file' => $image
            ]
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'disk' => $disk,
                'path' => $filePath,
                'url' => Storage::url($filePath),
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

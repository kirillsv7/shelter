<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Tests\FeatureTestCase;

class MediaFilesRequestsTest extends FeatureTestCase
{
    public function testMediaFileUpload(): void
    {
        $disk = 'public';

        Storage::fake($disk);

        $animal = AnimalModel::factory()->create();

        $image = UploadedFile::fake()->image($animal->name . '.jpg');

        $response = $this->post(
            route('mediafile.store'),
            [
                'model' => get_class(new AnimalModel()),
                'id' => (string)$animal->id,
                'file' => $image
            ]
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'disk' => $disk,
                'path' => 'images/animals/' . $image->hashName(),
                'url' => Storage::url('images/animals/' . $image->hashName()),
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

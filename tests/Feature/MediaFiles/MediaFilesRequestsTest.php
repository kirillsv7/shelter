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

        $response = $this->post(
            route('mediafile.store'),
            [
                'model' => get_class(new AnimalModel()),
                'id' => (string)$animal->id,
                'file' => UploadedFile::fake()->image($animal->name . '.jpg')
            ]
        );

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'disk' => $disk,
                'path' => 'animals/' . $animal->name .'.jpg',
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

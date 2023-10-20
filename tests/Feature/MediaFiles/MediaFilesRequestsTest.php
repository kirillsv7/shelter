<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $filePath = implode('/', [
            $animal->getTable(),
            $animal->id,
            'images',
            Str::before($image->hashName(), '.'),
            'original.' . $image->extension()
        ]);

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
                'path' => $filePath,
                'url' => Storage::url($filePath),
                'mediableType' => get_class(new AnimalModel()),
                'mediableId' => $animal->id,
            ]);
    }
}

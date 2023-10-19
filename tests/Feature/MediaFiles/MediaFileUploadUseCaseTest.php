<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Application\MediaFile\MediaFileUploadUseCase;
use Source\Domain\MediaFile\Services\Storage as StorageInterface;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Services\PublicStorage;
use Tests\FeatureTestCase;

class MediaFileUploadUseCaseTest extends FeatureTestCase
{
    public function testMediaUploaderIsWorking(): void
    {
        $animal = AnimalModel::factory()->create();

        $fileName = $animal->name . '.jpg';

        $folderName = 'animals';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $filePath = $folderName . '/' . $uploadedFile->hashName();

        $storageMock = \Mockery::mock(StorageInterface::class);

        $storageMock
            ->shouldReceive('saveFile')
            ->with($uploadedFile, $folderName)
            ->andReturn([
                'disk' => 'public',
                'path' => $filePath,
            ]);

        $mediaFileUploadUseCase = $this->app->make(MediaFileUploadUseCase::class);

        $mediaFileUploadUseCase->upload(
            $uploadedFile,
            $folderName,
            $animal,
            $animal->id
        );

        $this->assertDatabaseHas('media_files', [
            'disk' => 'public',
            'path' => 'images/' . $filePath,
            'mediable_type' => get_class($animal),
            'mediable_id' => $animal->id,
        ]);
    }

    public function testPublicStorageImplementationIsWorking(): void
    {
        Storage::fake('public');

        $fileName = fake()->firstName . '.jpg';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $publicStorage = $this->app->make(PublicStorage::class);

        $publicStorage->saveFile($uploadedFile);

        Storage::disk('public')->assertExists($uploadedFile->hashName());
    }
}

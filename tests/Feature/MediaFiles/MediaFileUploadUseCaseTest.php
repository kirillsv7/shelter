<?php

namespace Tests\Feature\MediaFiles;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Application\MediaFile\MediaFileUploadUseCase;
use Source\Domain\MediaFile\Contracts\Storage as StorageInterface;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Storages\PublicStorage;
use Tests\FeatureTestCase;

class MediaFileUploadUseCaseTest extends FeatureTestCase
{
    public function testMediaUploaderIsWorking(): void
    {
        $disk = 'public';

        Storage::fake($disk);

        $animal = AnimalModel::factory()->create();

        $fileName = $animal->name . '.jpg';

        $folderName = 'animals';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $filePath = implode('/', [
            $animal->getTable(),
            $animal->id,
            'images',
            $uploadedFile->hashName()
        ]);

        $storageMock = \Mockery::mock(StorageInterface::class);

        $storageMock
            ->shouldReceive('saveFile')
            ->with($uploadedFile, $folderName)
            ->andReturn([
                'disk' => $disk,
                'path' => $filePath,
            ]);

        $mediaFileUploadUseCase = $this->app->make(MediaFileUploadUseCase::class);

        $mediaFileUploadUseCase->upload(
            $uploadedFile,
            $filePath,
            $animal,
            $animal->id
        );

        $this->assertDatabaseHas('media_files', [
            'disk' => $disk,
            'path' => $filePath,
            'mediable_type' => get_class($animal),
            'mediable_id' => $animal->id,
        ]);
    }

    public function testPublicStorageImplementationIsWorking(): void
    {
        $disk = 'public';

        Storage::fake($disk);

        $fileName = fake()->firstName . '.jpg';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $publicStorage = $this->app->make(PublicStorage::class);

        $savedFile = $publicStorage->saveFile(
            $uploadedFile,
            $uploadedFile->hashName()
        );

        Storage::disk($disk)->assertExists($uploadedFile->hashName());
    }
}

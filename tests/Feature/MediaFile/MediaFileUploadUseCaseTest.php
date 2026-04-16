<?php

namespace Tests\Feature\MediaFile;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Source\Application\MediaFile\UseCases\MediaFileUploadUseCase;
use Source\Domain\MediaFile\Contracts\Storage as StorageInterface;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Enums\MediableModel;
use Source\Infrastructure\MediaFile\Storages\PublicStorage;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Interface\MediaFile\DTOs\MediaFileStoreRequestDTO;
use Tests\FeatureTestCase;

class MediaFileUploadUseCaseTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        OrganizationModel::factory(3)->create();
    }

    public function testMediaUploaderIsWorking(): void
    {
        $disk = 'public';

        Storage::fake($disk);

        $animal = AnimalModel::factory()->create();

        $fileName = $animal->name . '.jpg';

        $folderName = 'animals';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $fileRoute = implode('/', [
            $animal->getTable(),
            $animal->id,
            'images',
        ]);

        $fileName = $uploadedFile->hashName();

        $filePath = $fileRoute . DIRECTORY_SEPARATOR . $fileName;

        $storageMock = \Mockery::mock(StorageInterface::class);

        $storageMock
            ->shouldReceive('saveFile')
            ->with($uploadedFile, $folderName)
            ->andReturn([
                'disk' => $disk,
                'path' => $filePath,
            ]);

        $mediaFileUploadUseCase = $this->app->make(MediaFileUploadUseCase::class);

        $mediaFileResponseDTO = $mediaFileUploadUseCase->upload(
            new MediaFileStoreRequestDTO(
                MediableModel::fromName('Animal'),
                $animal->id,
                $uploadedFile,
            )
        );

        $mediaFileData = $mediaFileResponseDTO->jsonSerialize()->jsonSerialize();

        $this->assertDatabaseHas('media_files', [
            'id' => $mediaFileData['id'],
            'storage_info' => json_encode([
                'disk' => $mediaFileData['storageInfo']['disk'],
                'route' => $mediaFileData['storageInfo']['route'],
                'fileName' => $mediaFileData['storageInfo']['fileName'],
            ]),
            'mimetype' => $mediaFileData['mimetype'],
            'mediable_type' => get_class($animal),
            'mediable_id' => $animal->id,
        ]);
    }

    public function testPublicStorageImplementationIsWorking(): void
    {
        $disk = 'public';

        $path = 'test';

        Storage::fake($disk);

        $fileName = fake()->firstName . '.jpg';

        $uploadedFile = UploadedFile::fake()->image($fileName);

        $publicStorage = $this->app->make(PublicStorage::class);

        $savedFile = $publicStorage->saveFile(
            $uploadedFile,
            $path,
            $uploadedFile->hashName(),
        );

        Storage::disk($disk)
            ->assertExists($path . DIRECTORY_SEPARATOR . $uploadedFile->hashName());
    }
}

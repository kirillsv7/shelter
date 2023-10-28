<?php

namespace MediaFile;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Events\AnimalDeleted;
use Source\Domain\Animal\Events\AnimalPublished;
use Source\Domain\Animal\Events\AnimalStatusChanged;
use Source\Domain\Animal\Events\AnimalUnpublished;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Animal\ValueObjects\Slug;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\MediaFile\Storages\PublicStorage;
use Tests\UnitTestCase;

class MediaFileTest extends UnitTestCase
{
    public function testMediaFileCreate()
    {
        $mediaFile = $this->mediaFileCreate();

        $this->assertEventsHas(MediaFileCreated::class, $mediaFile->releaseEvents());
    }

    public function testMediaFileConvertToArray()
    {
        $mediaFile = $this->mediaFileCreate();

        $mediaFileArray = $mediaFile->toArray();

        $this->assertIsArray($mediaFileArray);

        $this->assertEquals($mediaFileArray, [
            'id' => $mediaFile->id(),
            'disk' => $mediaFile->disk(),
            'path' => $mediaFile->path(),
            'mediableType' => $mediaFile->mediableType(),
            'mediableId' => $mediaFile->mediableId(),
        ]);
    }

    private function mediaFileCreate(): MediaFile
    {
        return MediaFile::create(
            id: Uuid::uuid4(),
            disk: 'public',
            path: 'media_files/test.jpg',
            mediableType: new AnimalModel(),
            mediableId: Uuid::uuid4()
        );
    }
}

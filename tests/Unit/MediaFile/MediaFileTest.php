<?php

namespace Tests\Unit\MediaFile;

use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Aggregates\StorageInfo;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalModel;
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
            'id' => $mediaFile->id,
            'storage_info' => $mediaFile->storageInfo->toArray(),
            'sizes' => $mediaFile->sizes(),
            'mimetype' => $mediaFile->mimetype->value(),
            'mediableType' => $mediaFile->mediableType()->value(),
            'mediableId' => $mediaFile->mediableId,
            'created_at' => $mediaFile->createdAt,
            'updated_at' => $mediaFile->updatedAt,
        ]);
    }

    private function mediaFileCreate(): MediaFile
    {
        return MediaFile::create(
            id: Uuid::uuid4(),
            storageInfo: StorageInfo::make(
                disk: StringValueObject::fromString('public'),
                route: StringValueObject::fromString('media_files'),
                fileName: StringValueObject::fromString('test.jpg')
            ),
            sizes: [],
            mimetype: StringValueObject::fromString('image/jpeg'),
            mediableType: new AnimalModel(),
            mediableId: Uuid::uuid4(),
            createdAt: Carbon::now()
        );
    }
}

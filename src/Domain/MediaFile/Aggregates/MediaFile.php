<?php

namespace Source\Domain\MediaFile\Aggregates;

use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Shared\Entity;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class MediaFile implements Entity, AggregateWithEvents
{
    use UseAggregateEvents;

    private function __construct(
        public readonly UuidInterface $id,
        public readonly StorageInfo $storageInfo,
        private array $sizes,
        public readonly StringValueObject $mimetype,
        public readonly BaseModel $mediableType,
        public readonly UuidInterface $mediableId,
        public readonly ?Carbon $createdAt = null,
        public readonly ?Carbon $updatedAt = null
    ) {
    }

    public static function make(
        UuidInterface $id,
        StorageInfo $storageInfo,
        array $sizes,
        StringValueObject $mimetype,
        BaseModel $mediableType,
        UuidInterface $mediableId,
        Carbon $createdAt = null,
        Carbon $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            storageInfo: $storageInfo,
            sizes: $sizes,
            mimetype: $mimetype,
            mediableType: $mediableType,
            mediableId: $mediableId,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        StorageInfo $storageInfo,
        array $sizes,
        StringValueObject $mimetype,
        BaseModel $mediableType,
        UuidInterface $mediableId,
        Carbon $createdAt = null,
        Carbon $updatedAt = null,
    ): self {
        $mediaFile = self::make(
            id: $id,
            storageInfo: $storageInfo,
            sizes: $sizes,
            mimetype: $mimetype,
            mediableType: $mediableType,
            mediableId: $mediableId,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $mediaFile->addEvent(new MediaFileCreated($mediaFile->id));

        return $mediaFile;
    }

    public function sizes(): array
    {
        return  $this->sizes;
    }

    public function filePath(): string
    {
        return $this->storageInfo->route . DIRECTORY_SEPARATOR . $this->storageInfo->fileName;
    }

    public function mediableType(): StringValueObject
    {
        return StringValueObject::fromString(get_class($this->mediableType));
    }

    public function addSize(string $size): void
    {
        $this->sizes[] = $size;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'storage_info' => $this->storageInfo->toArray(),
            'sizes' => $this->sizes(),
            'mimetype' => $this->mimetype->value(),
            'mediableType' => $this->mediableType()->value(),
            'mediableId' => $this->mediableId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}

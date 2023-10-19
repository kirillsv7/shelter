<?php

namespace Source\Domain\MediaFile\Aggregates;

use Illuminate\Filesystem\FilesystemManager;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Shared\Entity;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class MediaFile implements Entity, AggregateWithEvents
{
    use UseAggregateEvents;

    private function __construct(
        protected FilesystemManager $filesystemManager,
        private readonly UuidInterface $id,
        private string $disk,
        private string $path,
        private readonly BaseModel $mediableType,
        private readonly UuidInterface $mediableId,
    ) {
    }

    public static function create(
        UuidInterface $id,
        string $disk,
        string $path,
        BaseModel $mediableType,
        UuidInterface $mediableId,
    ): MediaFile {
        return new self(
            filesystemManager: new FilesystemManager(app()),
            id: $id,
            disk: $disk,
            path: $path,
            mediableType: $mediableType,
            mediableId: $mediableId,
        );
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function disk(): string
    {
        return $this->disk;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function url(): string
    {
        return $this->filesystemManager->url($this->path());
    }

    public function mediableType(): string
    {
        return get_class($this->mediableType);
    }

    public function mediableId(): UuidInterface
    {
        return $this->mediableId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'disk' => $this->disk(),
            'path' => $this->path(),
            'url' => $this->url(),
            'mediableType' => $this->mediableType(),
            'mediableId' => $this->mediableId(),
        ];
    }
}

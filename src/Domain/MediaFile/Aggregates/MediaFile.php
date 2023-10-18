<?php

namespace Source\Domain\MediaFile\Aggregates;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Infrastructure\Laravel\Models\BaseModel;

class MediaFile implements AggregateWithEvents
{
    use UseAggregateEvents;

    private function __construct(
        private readonly ?int $id,
        private string $disk,
        private string $path,
        private readonly BaseModel $mediableType,
        private readonly UuidInterface $mediableId,
    ) {
    }

    public static function create(
        ?int $id,
        string $disk,
        string $path,
        BaseModel $mediableType,
        UuidInterface $mediableId,
    ): MediaFile {
        return new self(
            id: $id,
            disk: $disk,
            path: $path,
            mediableType: $mediableType,
            mediableId: $mediableId,
        );
    }

    public function id(): int
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

    public function fullPath(): string
    {
        return $this->disk() . '/' . $this->path();
    }

    public function mediableType(): string
    {
        return get_class($this->mediableType);
    }

    public function mediableId(): UuidInterface
    {
        return $this->mediableId;
    }
}

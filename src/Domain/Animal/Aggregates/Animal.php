<?php

namespace Source\Domain\Animal\Aggregates;

use Carbon\CarbonInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalDeleted;
use Source\Domain\Animal\Events\AnimalPublished;
use Source\Domain\Animal\Events\AnimalStatusChanged;
use Source\Domain\Animal\Events\AnimalUnpublished;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;
use Source\Domain\Slug\ValueObjects\SlugString;

final class Animal implements AggregateWithEvents
{
    use UseAggregateEvents;

    private ?SlugString $slug = null;

    private function __construct(
        protected readonly UuidInterface $id,
        protected readonly AnimalInfo $info,
        protected AnimalStatus $status,
        protected int|bool $published = false,
        protected readonly ?CarbonInterface $createdAt = null,
        protected readonly ?CarbonInterface $updatedAt = null,
    ) {
    }

    public static function make(
        UuidInterface $id,
        AnimalInfo $info,
        AnimalStatus $status = AnimalStatus::Quarantine,
        int|bool $published = false,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            info: $info,
            status: $status,
            published: $published,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        AnimalInfo $info,
        AnimalStatus $status = AnimalStatus::Quarantine,
        int|bool $published = false,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        $animal = self::make(
            id: $id,
            info: $info,
            status: $status,
            published: $published,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $animal->addEvent(
            new AnimalCreated(
                $animal->id(),
                $animal->info()->name(),
            ),
        );

        return $animal;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function info(): AnimalInfo
    {
        return $this->info;
    }

    public function age(): IntegerValueObject
    {
        return IntegerValueObject::fromInteger($this->info()->birthdate()->age);
    }

    public function status(): AnimalStatus
    {
        return $this->status;
    }

    public function published(): bool
    {
        return $this->published;
    }

    public function createdAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CarbonInterface
    {
        return $this->updatedAt;
    }

    public function slug(): ?SlugString
    {
        return $this->slug;
    }

    public function changeStatus(AnimalStatus $status): void
    {
        if ($this->status === $status) {
            return;
        }

        $oldStatus = $this->status;
        $this->status = $status;

        $this->addEvent(
            new AnimalStatusChanged(
                $this->id(),
                $this->info()->name(),
                $this->status(),
                $oldStatus,
            ),
        );
    }

    public function publish(): void
    {
        if ($this->published) {
            return;
        }

        $this->published = true;
        $this->addEvent(new AnimalPublished($this->id()));
    }

    public function unpublish(): void
    {
        if (!$this->published) {
            return;
        }

        $this->published = false;
        $this->addEvent(new AnimalUnpublished($this->id()));
    }

    public function delete(): void
    {
        $this->addEvent(new AnimalDeleted($this->id()));
    }

    public function addSlug(SlugString $slug): void
    {
        $this->slug = $slug;
    }
}

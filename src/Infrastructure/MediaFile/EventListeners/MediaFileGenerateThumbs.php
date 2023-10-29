<?php

namespace Source\Infrastructure\MediaFile\EventListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Infrastructure\MediaFile\Services\ImageThumbsGenerator;

final readonly class MediaFileGenerateThumbs //implements ShouldQueue
{
    public function __construct(
        protected ImageThumbsGenerator $imageThumbsGenerator
    ) {
    }

    public function handle(
        MediaFileCreated $mediaFileCreated
    ): void {
        $this->imageThumbsGenerator->process($mediaFileCreated->id);
    }
}

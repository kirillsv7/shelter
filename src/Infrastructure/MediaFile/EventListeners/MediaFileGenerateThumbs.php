<?php

namespace Source\Infrastructure\MediaFile\EventListeners;

use Source\Domain\MediaFile\Events\MediaFileCreated;

final readonly class MediaFileGenerateThumbs
{
    public function handle(MediaFileCreated $mediaFileCreated)
    {
    }
}

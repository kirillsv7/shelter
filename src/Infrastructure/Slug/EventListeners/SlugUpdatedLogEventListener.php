<?php

namespace Source\Infrastructure\Slug\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Slug\Events\SlugUpdated;

final readonly class SlugUpdatedLogEventListener
{
    public function handle(SlugUpdated $slugUpdated): void
    {
        Log::channel('development')
            ->info('Slug updated', [
                'id' => (string)$slugUpdated->slug->id,
                'old' => $slugUpdated->oldValue->value(),
                'new' => $slugUpdated->slug->value(),
            ]);
    }
}

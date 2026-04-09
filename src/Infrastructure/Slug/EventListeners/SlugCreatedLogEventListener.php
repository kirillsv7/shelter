<?php

namespace Source\Infrastructure\Slug\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Slug\Events\SlugCreated;

final readonly class SlugCreatedLogEventListener
{
    public function handle(SlugCreated $slugCreated): void
    {
        Log::channel('development')
            ->info('New slug created', [
                'id' => (string)$slugCreated->slug->id,
                'value' => $slugCreated->slug->value(),
            ]);
    }
}

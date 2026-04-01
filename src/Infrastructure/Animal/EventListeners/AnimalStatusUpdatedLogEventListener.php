<?php

namespace Source\Infrastructure\Animal\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Animal\Events\AnimalStatusUpdated;

final readonly class AnimalStatusUpdatedLogEventListener
{
    public function handle(AnimalStatusUpdated $animalStatusUpdated): void
    {
        Log::channel('development')
            ->info('New animal status', [
                'id' => (string)$animalStatusUpdated->id,
                'name' => (string)$animalStatusUpdated->name->value(),
                'new status' => $animalStatusUpdated->newStatus->value,
                'old status' => $animalStatusUpdated->oldStatus->value,
            ]);
    }
}

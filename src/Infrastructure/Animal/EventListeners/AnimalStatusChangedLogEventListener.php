<?php

namespace Source\Infrastructure\Animal\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Animal\Events\AnimalStatusChanged;

final readonly class AnimalStatusChangedLogEventListener
{
    public function handle(AnimalStatusChanged $animalStatusChanged): void
    {
        Log::channel('development')
            ->info('New animal status', [
                'id' => (string)$animalStatusChanged->id,
                'name' => (string)$animalStatusChanged->name->value(),
                'new status' => $animalStatusChanged->newStatus->value,
                'old status' => $animalStatusChanged->oldStatus->value,
            ]);
    }
}

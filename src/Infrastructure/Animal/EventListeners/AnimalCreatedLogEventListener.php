<?php

namespace Source\Infrastructure\Animal\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Animal\Events\AnimalCreated;

final readonly class AnimalCreatedLogEventListener
{
    public function handle(AnimalCreated $animalCreated): void
    {
        Log::channel('debugging')
            ->info('New animal created', [
                'id' => (string)$animalCreated->id,
                'name' => $animalCreated->name->value()
            ]);
    }
}

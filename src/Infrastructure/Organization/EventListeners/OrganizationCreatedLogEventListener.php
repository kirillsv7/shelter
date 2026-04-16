<?php

namespace Source\Infrastructure\Organization\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Organization\Events\OrganizationCreated;

final readonly class OrganizationCreatedLogEventListener
{
    public function handle(OrganizationCreated $event): void
    {
        Log::channel('development')
            ->info('Organization created', [
                'id' => $event->organization->id,
                'name' => $event->organization->name,
            ]);
    }
}

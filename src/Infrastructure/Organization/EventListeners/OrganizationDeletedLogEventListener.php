<?php

namespace Source\Infrastructure\Organization\EventListeners;

use Illuminate\Support\Facades\Log;
use Source\Domain\Organization\Events\OrganizationDeleted;

final readonly class OrganizationDeletedLogEventListener
{
    public function handle(OrganizationDeleted $event): void
    {
        Log::channel('development')
            ->info('Organization deleted', [
                'id' => $event->organization->id,
                'name' => $event->organization->name,
            ]);
    }
}

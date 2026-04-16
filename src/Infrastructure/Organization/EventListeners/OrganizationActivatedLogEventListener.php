<?php

namespace Source\Infrastructure\Organization\EventListeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Source\Domain\Organization\Events\OrganizationActivated;

final readonly class OrganizationActivatedLogEventListener
{
    public function handle(OrganizationActivated $event): void
    {
        Log::channel('development')
            ->info('Organization activated', [
                'id' => $event->organization->id,
                'name' => $event->organization->name,
                'activatedBy' => Auth::user()->getAttribute('id'),
            ]);
    }
}

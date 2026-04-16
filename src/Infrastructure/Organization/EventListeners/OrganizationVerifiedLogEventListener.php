<?php

namespace Source\Infrastructure\Organization\EventListeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Source\Domain\Organization\Events\OrganizationVerified;

final readonly class OrganizationVerifiedLogEventListener
{
    public function handle(OrganizationVerified $event): void
    {
        Log::channel('development')
            ->info('Organization verified', [
                'id' => $event->organization->id,
                'name' => $event->organization->name,
                'verifiedBy' => Auth::user()->getAttribute('id'),
            ]);
    }
}

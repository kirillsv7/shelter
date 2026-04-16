<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalStatusUpdated;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Domain\Organization\Events\OrganizationActivated;
use Source\Domain\Organization\Events\OrganizationCreated;
use Source\Domain\Organization\Events\OrganizationDeactivated;
use Source\Domain\Organization\Events\OrganizationDeleted;
use Source\Domain\Organization\Events\OrganizationVerified;
use Source\Domain\Slug\Events\SlugCreated;
use Source\Domain\Slug\Events\SlugUpdated;
use Source\Infrastructure\Animal\EventListeners\AnimalCreatedLogEventListener;
use Source\Infrastructure\Animal\EventListeners\AnimalStatusUpdatedLogEventListener;
use Source\Infrastructure\MediaFile\EventListeners\MediaFileGenerateThumbs;
use Source\Infrastructure\Organization\EventListeners\OrganizationActivatedLogEventListener;
use Source\Infrastructure\Organization\EventListeners\OrganizationCreatedLogEventListener;
use Source\Infrastructure\Organization\EventListeners\OrganizationDeactivatedLogEventListener;
use Source\Infrastructure\Organization\EventListeners\OrganizationDeletedLogEventListener;
use Source\Infrastructure\Organization\EventListeners\OrganizationVerifiedLogEventListener;
use Source\Infrastructure\Slug\EventListeners\SlugCreatedLogEventListener;
use Source\Infrastructure\Slug\EventListeners\SlugUpdatedLogEventListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AnimalCreated::class => [
            AnimalCreatedLogEventListener::class,
        ],

        AnimalStatusUpdated::class => [
            AnimalStatusUpdatedLogEventListener::class,
        ],

        OrganizationCreated::class => [
            OrganizationCreatedLogEventListener::class,
        ],

        OrganizationVerified::class => [
            OrganizationVerifiedLogEventListener::class,
        ],

        OrganizationActivated::class => [
            OrganizationActivatedLogEventListener::class,
        ],

        OrganizationDeactivated::class => [
            OrganizationDeactivatedLogEventListener::class,
        ],

        OrganizationDeleted::class => [
            OrganizationDeletedLogEventListener::class,
        ],

        MediaFileCreated::class => [
            MediaFileGenerateThumbs::class,
        ],

        SlugCreated::class => [
            SlugCreatedLogEventListener::class,
        ],

        SlugUpdated::class => [
            SlugUpdatedLogEventListener::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

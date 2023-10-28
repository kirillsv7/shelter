<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalStatusChanged;
use Source\Domain\MediaFile\Events\MediaFileCreated;
use Source\Infrastructure\Animal\EventListeners\AnimalCreatedLogEventListener;
use Source\Infrastructure\Animal\EventListeners\AnimalStatusChangedLogEventListener;
use Source\Infrastructure\MediaFile\EventListeners\MediaFileGenerateThumbs;

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
            AnimalCreatedLogEventListener::class
        ],
        AnimalStatusChanged::class => [
            AnimalStatusChangedLogEventListener::class
        ],

        MediaFileCreated::class => [
            MediaFileGenerateThumbs::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

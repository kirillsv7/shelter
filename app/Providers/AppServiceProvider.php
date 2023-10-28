<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Source\Infrastructure\Laravel\Events\MultiDispatcher::class,
            \Source\Infrastructure\Laravel\Events\LaravelMultiDispatcher::class
        );

        $this->app->bind(
            \Source\Infrastructure\MediaFile\Services\MediaFilePathGenerator::class,
            \Source\Infrastructure\MediaFile\Services\PublicStorageMediaFilePathGenerator::class
        );

        $this->app->bind(
            \Source\Domain\Animal\Repositories\AnimalRepository::class,
            \Source\Infrastructure\Animal\Repositories\AnimalRepository::class
        );

        $this->app->bind(
            \Source\Domain\Slug\Repositories\SlugRepository::class,
            \Source\Infrastructure\Slug\Repositories\SlugRepository::class
        );

        $this->app->bind(
            \Source\Domain\MediaFile\Repositories\MediaFileRepository::class,
            \Source\Infrastructure\MediaFile\Repositories\MediaFileRepository::class
        );

        $this->app->bind(
            \Source\Domain\MediaFile\Contracts\Storage::class,
            \Source\Infrastructure\MediaFile\Storages\PublicStorage::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(app()->isLocal());
    }
}

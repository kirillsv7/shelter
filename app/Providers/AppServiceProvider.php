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
            \Source\Domain\Animal\Repositories\AnimalRepository::class,
            \Source\Infrastructure\Animal\Repositories\AnimalRepository::class
        );

        $this->app->bind(
            \Source\Domain\Slug\Repositories\SlugRepository::class,
            \Source\Infrastructure\Slug\Repositories\SlugRepository::class
        );

        $this->app->bind(
            \Source\MediaFiles\Services\Storage::class,
            \Source\MediaFiles\Services\PublicStorage::class
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

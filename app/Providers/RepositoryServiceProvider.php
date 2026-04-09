<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        \Source\Infrastructure\Laravel\Events\MultiDispatcher::class =>
            \Source\Infrastructure\Laravel\Events\LaravelMultiDispatcher::class,

        \Source\Domain\MediaFile\Contracts\MediaFileRouteGenerator::class =>
            \Source\Infrastructure\MediaFile\Services\PublicStorageMediaFileRouteGenerator::class,

        \Source\Domain\MediaFile\Contracts\MediaFileNameGenerator::class =>
            \Source\Infrastructure\MediaFile\Services\GeneralMediaFileNameGenerator::class,

        \Source\Domain\Animal\Repositories\AnimalRepository::class =>
            \Source\Infrastructure\Animal\Repositories\AnimalRepository::class,
        \Source\Domain\Animal\Repositories\AnimalStatusRepository::class =>
            \Source\Infrastructure\Animal\Repositories\AnimalStatusRepository::class,
        \Source\Domain\Slug\Repositories\SlugRepository::class =>
            \Source\Infrastructure\Slug\Repositories\SlugRepository::class,
        \Source\Domain\MediaFile\Repositories\MediaFileRepository::class =>
            \Source\Infrastructure\MediaFile\Repositories\MediaFileRepository::class,
        \Source\Domain\MediaFile\Contracts\Storage::class =>
            \Source\Infrastructure\MediaFile\Storages\PublicStorage::class,
    ];
}

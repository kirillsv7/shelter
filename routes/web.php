<?php

use Illuminate\Support\Facades\Route;
use Source\Interface\Animal\Controllers\AnimalController;
use Source\Interface\MediaFile\Controllers\MediaFileController;
use Source\Interface\Slug\Controllers\SlugController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => config('app.name') . ' is running')->name('home');

Route::group([
    'controller' => AnimalController::class,
    'prefix' => 'animals',
    'as' => 'animals.',
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'getById')->name('get-by-id');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
    Route::put('/status/{id}', 'statusUpdate')->name('status-update');
    Route::post('/publish/{id}', 'publish')->name('publish');
    Route::post('/unpublish/{id}', 'unpublish')->name('unpublish');
});

Route::post('mediafiles', [MediaFileController::class, 'store'])->name('media-file.store');
Route::get('mediafiles/{id}', [MediaFileController::class, 'getById'])->name('media-file.get-by-id');

Route::post('slugs/{id}', [SlugController::class, 'update'])->name('slug.update');

Route::get('{animal}/{slug}', [AnimalController::class, 'getBySlug'])->name('animals.get-by-slug');
Route::get('{animals}', [AnimalController::class, 'indexByType'])->name('animals.index-by-type');

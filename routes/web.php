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

Route::get('/', function () {
    return config('app.name') . ' is running';
});

Route::get('animals', [AnimalController::class, 'index'])->name('animal.index');
Route::get('{animals}', [AnimalController::class, 'indexByType'])->name('animal.index-by-type');

Route::post('animals', [AnimalController::class, 'store'])->name('animal.store');
Route::get('animals/{id}', [AnimalController::class, 'getById'])->name('animal.get-by-id');
Route::put('animals/{id}', [AnimalController::class, 'update'])->name('animal.update');
Route::put('animals/status/{id}', [AnimalController::class, 'statusUpdate'])->name('animal.status-update');
Route::post('animals/publish/{id}', [AnimalController::class, 'publish'])->name('animal.publish');
Route::post('animals/unpublish/{id}', [AnimalController::class, 'unpublish'])->name('animal.unpublish');
Route::delete('animals/{id}', [AnimalController::class, 'destroy'])->name('animal.destroy');
Route::get('{animal}/{slug}', [AnimalController::class, 'getBySlug'])->name('animal.get-by-slug');

Route::post('slug/{id}', [SlugController::class, 'update'])->name('slug.update');

Route::post('mediafile', [MediaFileController::class, 'store'])->name('mediafile.store');

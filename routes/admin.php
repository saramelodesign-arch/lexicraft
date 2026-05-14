<?php

use App\Http\Controllers\Admin\ConceptController;
use App\Http\Controllers\Admin\ConceptMediaController;
use App\Http\Controllers\Admin\ConceptRelationController;
use App\Http\Controllers\Admin\ConceptTranslationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\SeoOverviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('media', [MediaLibraryController::class, 'index'])->name('media.index');

        Route::get('seo', [SeoOverviewController::class, 'index'])->name('seo.index');

        Route::resource('domains', DomainController::class)->except(['show']);

        Route::resource('concepts', ConceptController::class)->except(['show']);

        Route::post('concepts/{concept}/relations', [ConceptRelationController::class, 'store'])
            ->name('concepts.relations.store');
        Route::delete('concepts/{concept}/relations/{relation}', [ConceptRelationController::class, 'destroy'])
            ->name('concepts.relations.destroy');

        Route::post('concepts/{concept}/translations', [ConceptTranslationController::class, 'store'])
            ->name('concepts.translations.store');
        Route::put('concepts/{concept}/translations/{translation}', [ConceptTranslationController::class, 'update'])
            ->name('concepts.translations.update');

        Route::post('concepts/{concept}/media', [ConceptMediaController::class, 'store'])
            ->name('concepts.media.store');
        Route::patch('concepts/{concept}/media/{media}', [ConceptMediaController::class, 'update'])
            ->name('concepts.media.update');
        Route::delete('concepts/{concept}/media/{media}', [ConceptMediaController::class, 'destroy'])
            ->name('concepts.media.destroy');
    });

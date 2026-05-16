<?php

use App\Http\Controllers\DomainIndexController;
use App\Http\Controllers\DomainShowController;
use App\Http\Controllers\GlossaryConceptShowController;
use App\Http\Controllers\GlossaryLetterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningFlashcardsController;
use App\Http\Controllers\LearningHubController;
use App\Http\Controllers\LearningProgressController;
use App\Http\Controllers\LearningQuizShowController;
use App\Http\Controllers\LearningQuizzesIndexController;
use App\Http\Controllers\LearningSemanticController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SearchResultsController;
use App\Http\Controllers\SitemapConceptsController;
use App\Http\Controllers\SitemapDomainsController;
use App\Http\Controllers\SitemapIndexController;
use App\Http\Controllers\SitemapStaticController;
use App\Support\Locales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsTxtController::class)->name('robots');
Route::get('/sitemap.xml', SitemapIndexController::class)->name('sitemap');
Route::get('/sitemaps/static-{locale}.xml', SitemapStaticController::class)
    ->where('locale', Locales::localeRoutePattern())
    ->name('sitemaps.static');
Route::get('/sitemaps/concepts-{locale}-{chunk}.xml', SitemapConceptsController::class)
    ->where(['locale' => Locales::localeRoutePattern(), 'chunk' => '[0-9]+'])
    ->name('sitemaps.concepts');
Route::get('/sitemaps/domains-{locale}.xml', SitemapDomainsController::class)
    ->where('locale', Locales::localeRoutePattern())
    ->name('sitemaps.domains');

Route::get('/', function (Request $request) {
    return redirect()->route('home', ['locale' => Locales::preferredFromRequest($request)]);
})->name('root.redirect');

Route::prefix('{locale}')
    ->where(['locale' => Locales::localeRoutePattern()])
    ->middleware('locale')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::get('search', SearchResultsController::class)->middleware('throttle:search')->name('search');

        Route::get('learning', LearningHubController::class)->name('learning.index');
        Route::get('learning/flashcards', LearningFlashcardsController::class)->name('learning.flashcards');
        Route::get('learning/quizzes', LearningQuizzesIndexController::class)->name('learning.quizzes');
        Route::get('learning/quiz/{slug}', LearningQuizShowController::class)
            ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
            ->name('learning.quiz.show');
        Route::get('learning/semantic', LearningSemanticController::class)->name('learning.semantic');
        Route::get('learning/progress', LearningProgressController::class)->middleware(['auth', 'verified'])->name('learning.progress');

        Route::get('domains', DomainIndexController::class)->name('domains.index');
        Route::get('domains/{slug}', DomainShowController::class)
            ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
            ->name('domains.show');

        Route::get('glossary/{letter}', GlossaryLetterController::class)
            ->where(['letter' => '[A-Za-z]'])
            ->name('glossary.letter');

        Route::get('glossary/{slug}', GlossaryConceptShowController::class)
            ->where(['slug' => '[a-z0-9]+(?:-[a-z0-9]+)*'])
            ->name('glossary.concept');
    });

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/admin.php';

require __DIR__.'/settings.php';

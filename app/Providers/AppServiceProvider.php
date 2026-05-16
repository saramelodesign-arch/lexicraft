<?php

namespace App\Providers;

use App\Http\Responses\FortifyLogoutResponse;
use App\Models\ConceptRelation;
use App\Models\DomainTranslation;
use App\Models\Example;
use App\Models\User;
use App\Support\Search\QueuedSearchIndexer;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LogoutResponse::class, FortifyLogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', fn (User $user): bool => $user->canAccessAdmin());

        $this->configureDefaults();
        $this->configureRateLimiters();
        $this->configureConceptSearchIndexHooks();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Register named throttles for abuse-sensitive public endpoints.
     */
    protected function configureRateLimiters(): void
    {
        RateLimiter::for('search', function (Request $request): Limit {
            $identity = $request->user()?->id !== null
                ? 'user:'.$request->user()->id
                : 'ip:'.$request->ip();

            return Limit::perMinute(90)->by('search:'.$identity);
        });
    }

    /**
     * Keep Meilisearch / Scout documents aligned when semantic or domain copy changes.
     */
    protected function configureConceptSearchIndexHooks(): void
    {
        ConceptRelation::saved(function (ConceptRelation $relation): void {
            QueuedSearchIndexer::queueConcept((int) $relation->concept_id);
            QueuedSearchIndexer::queueConcept((int) $relation->related_concept_id);
        });

        ConceptRelation::deleted(function (ConceptRelation $relation): void {
            QueuedSearchIndexer::queueConcept((int) $relation->concept_id);
            QueuedSearchIndexer::queueConcept((int) $relation->related_concept_id);
        });

        Example::saved(function (Example $example): void {
            QueuedSearchIndexer::queueTranslation((int) $example->concept_translation_id);
        });

        Example::deleted(function (Example $example): void {
            QueuedSearchIndexer::queueTranslation((int) $example->concept_translation_id);
        });

        DomainTranslation::saved(function (DomainTranslation $translation): void {
            $this->queueDomainConceptsForReindex($translation);
        });

        DomainTranslation::deleted(function (DomainTranslation $translation): void {
            $this->queueDomainConceptsForReindex($translation);
        });
    }

    protected function queueDomainConceptsForReindex(DomainTranslation $translation): void
    {
        $translation->domain?->concepts()->chunkById(50, function ($concepts): void {
            foreach ($concepts as $concept) {
                QueuedSearchIndexer::queueConcept((int) $concept->id);
            }
        });
    }
}

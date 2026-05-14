<?php

namespace App\Providers;

use App\Http\Responses\FortifyLogoutResponse;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\DomainTranslation;
use App\Models\Example;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
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
     * Keep Meilisearch / Scout documents aligned when semantic or domain copy changes.
     */
    protected function configureConceptSearchIndexHooks(): void
    {
        ConceptRelation::saved(function (ConceptRelation $relation): void {
            self::reindexConceptTranslationsForSearch((int) $relation->concept_id);
            self::reindexConceptTranslationsForSearch((int) $relation->related_concept_id);
        });

        ConceptRelation::deleted(function (ConceptRelation $relation): void {
            self::reindexConceptTranslationsForSearch((int) $relation->concept_id);
            self::reindexConceptTranslationsForSearch((int) $relation->related_concept_id);
        });

        Example::saved(function (Example $example): void {
            $example->conceptTranslation?->searchable();
        });

        Example::deleted(function (Example $example): void {
            if ($example->concept_translation_id !== null) {
                ConceptTranslation::query()->find($example->concept_translation_id)?->searchable();
            }
        });

        DomainTranslation::saved(function (DomainTranslation $translation): void {
            $translation->domain?->concepts()->chunkById(50, function ($concepts): void {
                foreach ($concepts as $concept) {
                    self::reindexConceptTranslationsForSearch((int) $concept->id);
                }
            });
        });
    }

    private static function reindexConceptTranslationsForSearch(int $conceptId): void
    {
        ConceptTranslation::query()->where('concept_id', $conceptId)->chunkById(100, function ($chunk): void {
            $chunk->searchable();
        });
    }
}

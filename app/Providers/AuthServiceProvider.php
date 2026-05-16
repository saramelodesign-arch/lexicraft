<?php

namespace App\Providers;

use App\Models\Concept;
use App\Models\Domain;
use App\Policies\ConceptPolicy;
use App\Policies\DomainPolicy;
use App\Policies\EditorialPolicy;
use App\Policies\LearningPolicy;
use App\Policies\MediaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Concept::class, ConceptPolicy::class);
        Gate::policy(Domain::class, DomainPolicy::class);

        Gate::define('media.library.view', [MediaPolicy::class, 'viewLibrary']);
        Gate::define('media.manage', [MediaPolicy::class, 'manageForConcept']);

        Gate::define('learning.progress.view', [LearningPolicy::class, 'viewProgress']);

        Gate::define('editorial.dashboard.view', [EditorialPolicy::class, 'viewDashboard']);
        Gate::define('editorial.seo.view', [EditorialPolicy::class, 'viewSeoOverview']);
        Gate::define('editorial.actions.manage', [EditorialPolicy::class, 'manageActions']);
    }
}


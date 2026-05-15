<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Contracts\View\View;

final class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $activeLanguageCount = Language::query()->where('is_active', true)->count();

        $missingTranslationCount = $activeLanguageCount > 0
            ? Concept::query()
                ->whereRaw(
                    '(select count(*) from concept_translations where concept_translations.concept_id = concepts.id) < ?',
                    [$activeLanguageCount],
                )
                ->count()
            : 0;

        $draftConcepts = Concept::query()->where('status', WorkflowStatus::DRAFT)->count();
        $reviewConcepts = Concept::query()->where('status', WorkflowStatus::REVIEW)->count();
        $publishedConcepts = Concept::query()->where('status', WorkflowStatus::PUBLISHED)->count();
        $archivedConcepts = Concept::query()->where('status', WorkflowStatus::ARCHIVED)->count();

        $thinSeo = ConceptTranslation::query()
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn ($q) => $q->where('status', WorkflowStatus::PUBLISHED))
            ->where(function ($q): void {
                $q->whereNull('seo_title')->orWhere('seo_title', '');
            })
            ->where(function ($q): void {
                $q->whereNull('seo_description')->orWhere('seo_description', '');
            })
            ->count();

        $domainsActive = Domain::query()->where('is_active', true)->count();

        $recentConcepts = Concept::query()
            ->with(['translations' => fn ($q) => $q->with('language')->orderBy('language_id')])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', [
            'activeLanguageCount' => $activeLanguageCount,
            'missingTranslationCount' => $missingTranslationCount,
            'draftConcepts' => $draftConcepts,
            'publishedConcepts' => $publishedConcepts,
            'reviewConcepts' => $reviewConcepts,
            'archivedConcepts' => $archivedConcepts,
            'thinSeo' => $thinSeo,
            'domainsActive' => $domainsActive,
            'recentConcepts' => $recentConcepts,
        ]);
    }
}

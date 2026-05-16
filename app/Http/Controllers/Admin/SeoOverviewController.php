<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConceptTranslation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class SeoOverviewController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('editorial.seo.view');

        $query = ConceptTranslation::query()
            ->with(['concept', 'language'])
            ->whereHas('concept', fn ($q) => $q->where('status', 'published'));

        $locale = $request->string('locale')->toString();
        if ($locale !== '') {
            $query->whereHas('language', fn ($q) => $q->where('code', $locale));
        }

        $query->where(function ($q): void {
            $q->where(function ($w): void {
                $w->whereNull('seo_title')->orWhere('seo_title', '');
            })->orWhere(function ($w): void {
                $w->whereNull('seo_description')->orWhere('seo_description', '');
            })->orWhere(function ($w): void {
                $w->whereNull('og_title')->orWhere('og_title', '');
            });
        });

        $rows = $query->orderByDesc('updated_at')->paginate(40)->withQueryString();

        return view('admin.seo.index', [
            'translations' => $rows,
            'locale' => $locale,
        ]);
    }
}

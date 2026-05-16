<?php

namespace App\Http\Controllers;

use App\Support\Learning\RecordLearningProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

final class LearningProgressController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $user = $request->user();
        Gate::authorize('learning.progress.view', $user);

        $summary = RecordLearningProgress::summaryForUser($user);

        return view('pages.learning.progress', [
            'locale' => $locale,
            'summary' => $summary,
            'pageTitle' => __('learning.progress_title'),
            'metaDescription' => __('learning.progress_meta_description'),
            'canonical' => route('learning.progress', ['locale' => $locale], absolute: true),
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
